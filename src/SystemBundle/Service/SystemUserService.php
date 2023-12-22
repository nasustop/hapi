<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
namespace SystemBundle\Service;

use Hyperf\DbConnection\Db;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use SystemBundle\Repository\SystemPowerRepository;
use SystemBundle\Repository\SystemUserRelAccountRepository;
use SystemBundle\Repository\SystemUserRepository;

/**
 * @method getLists(array $filter = [], array|string $columns = '*', int $page = 0, int $pageSize = 0, array $orderBy = [])
 * @method count(array $filter)
 * @method pageLists(array $filter = [], array|string $columns = '*', int $page = 1, int $pageSize = 100, array $orderBy = [])
 * @method insert(array $data)
 * @method batchInsert(array $data)
 * @method saveData(array $data)
 * @method updateBy(array $filter, array $data)
 * @method updateOneBy(array $filter, array $data)
 * @method deleteBy(array $filter)
 * @method deleteOneBy(array $filter)
 */
class SystemUserService
{
    protected SystemUserRepository $repository;

    protected SystemUserRelAccountRepository $systemUserRelAccountRepository;

    protected SystemPowerRepository $powerRepository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): SystemUserRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(SystemUserRepository::class);
        }
        return $this->repository;
    }

    /**
     * get SystemUserRelAccountRepository.
     */
    public function getSystemUserRelAccountRepository(): SystemUserRelAccountRepository
    {
        if (empty($this->systemUserRelAccountRepository)) {
            $this->systemUserRelAccountRepository = make(SystemUserRelAccountRepository::class);
        }
        return $this->systemUserRelAccountRepository;
    }

    /**
     * get PowerRepository.
     */
    public function getPowerRepository(): SystemPowerRepository
    {
        if (empty($this->powerRepository)) {
            $this->powerRepository = make(SystemPowerRepository::class);
        }
        return $this->powerRepository;
    }

    public function getInfo(array $filter, array|string $columns = '*', array $orderBy = []): array
    {
        $info = $this->getRepository()->getInfo($filter, $columns, $orderBy);
        if (! empty($info)) {
            unset($info['password'], $info['password_hash']);
            $relAccount = $this->getSystemUserRelAccountRepository()
                ->getLists(['user_id' => $info['user_id']]);
            foreach ($relAccount as $value) {
                $info[$value['rel_type']] = $value['rel_value'];
            }
            $powerData = $this->getPowerRepository()->addPowerListByParentIds([$info['user_id']], SystemPowerRepository::ENUM_PARENT_TYPE_USER);
            $info['role_ids'] = $powerData[$info['user_id']]['role_ids'] ?? [];
            $info['menu_ids'] = $powerData[$info['user_id']]['menu_ids'] ?? [];
            $info['api_ids'] = $powerData[$info['user_id']]['api_ids'] ?? [];
        }
        return $info;
    }

    /**
     * @throws \Exception
     */
    public function createUser(array $data): array
    {
        Db::beginTransaction();
        try {
            $user_id = $this->getRepository()->insertGetId(data: $data);

            $batchInertRelAccount = [];
            foreach (SystemUserRelAccountRepository::ENUM_REL_TYPE as $type => $value) {
                if (! empty($data[$type])) {
                    $batchInertRelAccount[] = [
                        'user_id' => $user_id,
                        'rel_type' => $type,
                        'rel_value' => $data[$type],
                    ];
                }
            }
            if (! empty($batchInertRelAccount)) {
                $this->getSystemUserRelAccountRepository()->batchInsert($batchInertRelAccount);
            }

            $this->getPowerRepository()->batchInsertByAuth(
                $user_id,
                SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                $data['role_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_ROLE,
            );
            $this->getPowerRepository()->batchInsertByAuth(
                $user_id,
                SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                $data['menu_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            );
            $this->getPowerRepository()->batchInsertByAuth(
                $user_id,
                SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                $data['api_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_API,
            );

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getRepository()->getInfo(filter: ['user_id' => $user_id]);
    }

    /**
     * @throws \Exception
     */
    public function updateUser(array $filter, array $data): array
    {
        unset($data['password'], $data['password_hash']);
        $info = $this->getRepository()->getInfo(filter: $filter);
        if (empty($info)) {
            throw new BadRequestHttpException(message: '修改的数据不存在');
        }
        $relData = $this->getSystemUserRelAccountRepository()
            ->getLists(['user_id' => $info['user_id']]);
        $relData = array_column($relData, null, 'rel_type');
        Db::beginTransaction();
        try {
            $this->getRepository()->updateBy(filter: ['user_id' => $info['user_id']], data: $data);

            $relDeleteIds = [];
            $relInsertData = [];
            foreach (SystemUserRelAccountRepository::ENUM_REL_TYPE as $type => $value) {
                if (! empty($relData[$type])) {
                    if (empty($data[$type])) {
                        $relDeleteIds[] = $relData[$type]['id'];
                    } elseif ($data[$type] !== $relData[$type]) {
                        $this->systemUserRelAccountRepository->updateOneBy([
                            'id' => $relData[$type]['id'],
                        ], [
                            'rel_value' => $data[$type],
                        ]);
                    }
                } elseif (! empty($data[$type])) {
                    $relInsertData[] = [
                        'user_id' => $info['user_id'],
                        'rel_type' => $type,
                        'rel_value' => $data[$type],
                    ];
                }
            }

            if (! empty($relDeleteIds)) {
                $this->getSystemUserRelAccountRepository()->deleteBy(['id' => $relDeleteIds]);
            }

            if (! empty($relInsertData)) {
                $this->getSystemUserRelAccountRepository()->batchInsert($relInsertData);
            }

            $this->getPowerRepository()->batchInsertByAuth(
                $info['user_id'],
                SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                $data['role_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_ROLE,
            );
            $this->getPowerRepository()->batchInsertByAuth(
                $info['user_id'],
                SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                $data['menu_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            );
            $this->getPowerRepository()->batchInsertByAuth(
                $info['user_id'],
                SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                $data['api_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_API,
            );

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getRepository()->getInfo(filter: ['user_id' => $info['user_id']]);
    }

    /**
     * @throws \Exception
     */
    public function deleteUser(array $filter): bool
    {
        $info = $this->getRepository()->getInfo(filter: $filter);
        if (empty($info)) {
            throw new BadRequestHttpException('删除的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->deleteBy(filter: ['user_id' => $info['user_id']]);

            $this->getSystemUserRelAccountRepository()
                ->deleteBy(filter: ['user_id' => $info['user_id']]);

            $this->getPowerRepository()->deleteBy(filter: [
                'parent_id' => $info['user_id'],
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_USER,
            ]);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return true;
    }

    public function getUserLists(array $filter): array
    {
        $result = $this->getRepository()->getLists(filter: $filter);
        $parentIds = array_column($result, 'user_id');
        $powerData = $this->getPowerRepository()->addPowerListByParentIds($parentIds, SystemPowerRepository::ENUM_PARENT_TYPE_USER);
        foreach ($result as $k => $v) {
            $result[$k]['role_ids'] = $powerData[$v['user_id']]['role_ids'] ?? [];
            $result[$k]['menu_ids'] = $powerData[$v['user_id']]['menu_ids'] ?? [];
            $result[$k]['api_ids'] = $powerData[$v['user_id']]['api_ids'] ?? [];
        }
        return $result;
    }

    public function pageUserLists(array $filter, array|string $columns = '*', int $page = 1, int $pageSize = 20): array
    {
        $relFilter = [];
        foreach (SystemUserRelAccountRepository::ENUM_REL_TYPE as $type => $value) {
            if (! empty($filter[$type])) {
                $relFilter[] = [
                    'rel_type' => $type,
                    'rel_value|contains' => $filter[$type],
                ];
                unset($filter[$type]);
            }
        }
        if (! empty($relFilter)) {
            $relData = $this->getSystemUserRelAccountRepository()->getLists($relFilter, 'user_id');
            $relUserIds = array_values(array_unique(array_column($relData, 'user_id')));
            $filter['user_id'] = empty($filter['user_id']) ? $relUserIds : array_merge($filter['user_id'], $relUserIds);
        }
        $result = $this->getRepository()->pageLists(filter: $filter, columns: $columns, page: $page, pageSize: $pageSize);

        $parentIds = array_column($result['list'], 'user_id');
        $powerData = $this->getPowerRepository()->addPowerListByParentIds($parentIds, SystemPowerRepository::ENUM_PARENT_TYPE_USER);
        foreach ($result['list'] as $k => $v) {
            $result['list'][$k]['role_ids'] = $powerData[$v['user_id']]['role_ids'] ?? [];
            $result['list'][$k]['menu_ids'] = $powerData[$v['user_id']]['menu_ids'] ?? [];
            $result['list'][$k]['api_ids'] = $powerData[$v['user_id']]['api_ids'] ?? [];
        }
        return $result;
    }

    /**
     * 获取用户有权限的menu_ids.
     */
    public function getUserAllMenuIds(int $user_id): array
    {
        $powerList = [];
        if (! empty($user_id)) {
            $powerList = $this->getPowerRepository()->getLists(filter: [
                'parent_id' => $user_id,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_USER,
            ]);
        }
        $role_ids = [];
        $menu_ids = [];
        foreach ($powerList as $power) {
            if ($power['parent_id'] != $user_id || $power['parent_type'] != SystemPowerRepository::ENUM_PARENT_TYPE_USER) {
                continue;
            }
            if ($power['children_type'] == SystemPowerRepository::ENUM_CHILDREN_TYPE_ROLE) {
                $role_ids[] = $power['children_id'];
                continue;
            }
            if ($power['children_type'] == SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU) {
                $menu_ids[] = $power['children_id'];
            }
        }
        $role_ids = array_values(array_filter(array_unique($role_ids)));
        $powerList = [];
        if (! empty($role_ids)) {
            $powerList = $this->getPowerRepository()->getLists(filter: [
                'parent_id' => $role_ids,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                'children_type' => SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            ]);
        }
        foreach ($powerList as $power) {
            if (! in_array($power['parent_id'], $role_ids) || $power['parent_type'] != SystemPowerRepository::ENUM_PARENT_TYPE_ROLE) {
                continue;
            }
            $menu_ids[] = $power['children_id'];
        }

        return array_values(array_filter(array_unique($menu_ids)));
    }
}
