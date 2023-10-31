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

    public function getInfo(array $filter, array|string $columns = '*', array $orderBy = [])
    {
        $info = $this->getRepository()->getInfo($filter, $columns, $orderBy);
        if (! empty($info)) {
            $relAccount = $this->getSystemUserRelAccountRepository()
                ->getLists(['user_id' => $info['user_id']]);
            foreach ($relAccount as $value) {
                $info[$value['rel_type']] = $value['rel_value'];
            }
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
            if (! empty($data['account'])) {
                $batchInertRelAccount[] = [
                    'user_id' => $user_id,
                    'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT,
                    'rel_value' => $data['account'],
                ];
            }
            if (! empty($data['mobile'])) {
                $batchInertRelAccount[] = [
                    'user_id' => $user_id,
                    'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_MOBILE,
                    'rel_value' => $data['mobile'],
                ];
            }
            if (! empty($data['email'])) {
                $batchInertRelAccount[] = [
                    'user_id' => $user_id,
                    'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_EMAIL,
                    'rel_value' => $data['email'],
                ];
            }
            if (! empty($batchInertRelAccount)) {
                $this->getSystemUserRelAccountRepository()->batchInsert($batchInertRelAccount);
            }

            $insertData = $this->_filterInsertList(id: $user_id, menu_ids: $data['menu_ids'] ?? [], role_ids: $data['role_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert(data: $insertData);
            }

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
        $info = $this->getInfo(filter: $filter);
        if (empty($info)) {
            throw new BadRequestHttpException(message: '修改的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateBy(filter: ['user_id' => $info['user_id']], data: $data);

            $relDeleteIds = [];
            $relInsertData = [];
            if (! empty($info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT])) {
                if (empty($data['account'])) {
                    $relDeleteIds[] = $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT]['id'];
                } elseif ($data['account'] !== $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT]) {
                    $this->systemUserRelAccountRepository->updateOneBy([
                        'id' => $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT]['id'],
                    ], [
                        'rel_value' => $data['account'],
                    ]);
                }
            } elseif (! empty($data['account'])) {
                $relInsertData[] = [
                    'user_id' => $info['user_id'],
                    'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT,
                    'rel_value' => $data['account'],
                ];
            }
            if (! empty($info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_EMAIL])) {
                if (empty($data['email'])) {
                    $relDeleteIds[] = $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_EMAIL]['id'];
                } elseif ($data['email'] !== $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_EMAIL]) {
                    $this->systemUserRelAccountRepository->updateOneBy([
                        'id' => $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_EMAIL]['id'],
                    ], [
                        'rel_value' => $data['email'],
                    ]);
                }
            } elseif (! empty($data['email'])) {
                $relInsertData[] = [
                    'user_id' => $info['user_id'],
                    'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_EMAIL,
                    'rel_value' => $data['email'],
                ];
            }
            if (! empty($info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_MOBILE])) {
                if (empty($data['mobile'])) {
                    $relDeleteIds[] = $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_MOBILE]['id'];
                } elseif ($data['mobile'] !== $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_MOBILE]) {
                    $this->systemUserRelAccountRepository->updateOneBy([
                        'id' => $info['rel'][SystemUserRelAccountRepository::ENUM_REL_TYPE_MOBILE]['id'],
                    ], [
                        'rel_value' => $data['mobile'],
                    ]);
                }
            } elseif (! empty($data['mobile'])) {
                $relInsertData[] = [
                    'user_id' => $info['user_id'],
                    'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_MOBILE,
                    'rel_value' => $data['mobile'],
                ];
            }

            if (! empty($relDeleteIds)) {
                $this->getSystemUserRelAccountRepository()->deleteBy(['id' => $relDeleteIds]);
            }

            if (! empty($relInsertData)) {
                $this->getSystemUserRelAccountRepository()->batchInsert($relInsertData);
            }

            $this->getPowerRepository()->deleteBy(filter: [
                'parent_id' => $info['user_id'],
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_USER,
            ]);

            $insertData = $this->_filterInsertList(id: $info['user_id'], menu_ids: $data['menu_ids'] ?? [], role_ids: $data['role_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert(data: $insertData);
            }

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
        return $this->_addPowerToUserList(userList: $result);
    }

    public function pageUserLists(array $filter, array|string $columns = '*', int $page = 1, int $pageSize = 20): array
    {
        $result = $this->getRepository()->pageLists(filter: $filter, columns: $columns, page: $page, pageSize: $pageSize);
        $result['list'] = $this->_addPowerToUserList(userList: $result['list']);
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

    protected function _addPowerToUserList(array $userList)
    {
        $user_ids = array_values(array_filter(array_unique(array_column($userList, 'user_id'))));
        $relAccountList = [];
        $powerList = [];
        if (! empty($user_ids)) {
            $relAccountList = $this->getSystemUserRelAccountRepository()->getLists(filter: [
                'user_id' => $user_ids,
            ]);
            $powerList = $this->getPowerRepository()->getLists(filter: [
                'parent_id' => $user_ids,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_USER,
            ]);
        }
        foreach ($userList as &$value) {
            $value['rel'] = [];
            foreach ($relAccountList as $relAccount) {
                if ($relAccount['user_id'] != $value['user_id']) {
                    continue;
                }
                $value['rel'][$relAccount['rel_type']] = $relAccount;
            }
            $role_ids = [];
            $menu_ids = [];
            foreach ($powerList as $power) {
                if ($power['parent_id'] != $value['user_id']) {
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
            $value['role_ids'] = array_values(array_filter(array_unique($role_ids)));
            $value['menu_ids'] = array_values(array_filter(array_unique($menu_ids)));
        }

        return $userList;
    }

    protected function _filterInsertList(int $id, array $menu_ids, array $role_ids): array
    {
        $insert_list = [];
        foreach ($menu_ids as $menu_id) {
            if (empty($menu_id)) {
                continue;
            }
            $insert_list[] = [
                'parent_id' => $id,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                'children_id' => $menu_id,
                'children_type' => SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            ];
        }
        foreach ($role_ids as $role_id) {
            if (empty($role_id)) {
                continue;
            }
            $insert_list[] = [
                'parent_id' => $id,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_USER,
                'children_id' => $role_id,
                'children_type' => SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
            ];
        }

        return $insert_list;
    }
}
