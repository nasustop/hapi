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

use Exception;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use SystemBundle\Repository\SystemPowerRepository;
use SystemBundle\Repository\SystemUserRepository;

/**
 * @method getInfo(array $filter, array|string $columns = '*', array $orderBy = [])
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
    #[Inject]
    protected SystemUserRepository $repository;

    #[Inject]
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
        return $this->repository;
    }

    /**
     * get PowerRepository.
     */
    public function getPowerRepository(): SystemPowerRepository
    {
        return $this->powerRepository;
    }

    /**
     * @throws Exception
     */
    public function createUser(array $data): array
    {
        $mobileUser = $this->getRepository()->getInfo([
            'mobile' => $data['mobile'],
        ]);
        if (! empty($mobileUser)) {
            throw new BadRequestHttpException('手机号已被使用');
        }
        $loginNameUser = $this->getRepository()->getInfo([
            'login_name' => $data['login_name'],
        ]);
        if (! empty($loginNameUser)) {
            throw new BadRequestHttpException('登录账号已被使用');
        }
        Db::beginTransaction();
        try {
            $user_id = $this->getRepository()->insertGetId($data);

            $insertData = $this->_filterInsertList($user_id, $data['menu_ids'] ?? [], $data['role_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert($insertData);
            }

            Db::commit();
        } catch (Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getRepository()->getInfo(['user_id' => $user_id]);
    }

    /**
     * @param mixed $filter
     * @param mixed $data
     * @throws Exception
     */
    public function updateUser($filter, $data): array
    {
        $info = $this->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('修改的数据不存在');
        }
        Db::beginTransaction();
        try {
            if (empty($data['password'])) {
                unset($data['password']);
            }
            $this->getRepository()->updateBy(['user_id' => $info['user_id']], $data);

            $this->getPowerRepository()->deleteBy([
                'parent_id' => $info['user_id'],
                'parent_type' => SystemPowerRepository::PARENT_TYPE_USER,
            ]);

            $insertData = $this->_filterInsertList($info['user_id'], $data['menu_ids'] ?? [], $data['role_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert($insertData);
            }

            Db::commit();
        } catch (Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getRepository()->getInfo(['user_id' => $info['user_id']]);
    }

    /**
     * @param mixed $filter
     * @throws Exception
     */
    public function deleteUser($filter): bool
    {
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('删除的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->deleteBy(['role_id' => $info['user_id']]);

            $this->getPowerRepository()->deleteBy([
                'parent_id' => $info['user_id'],
                'parent_type' => SystemPowerRepository::PARENT_TYPE_USER,
            ]);

            Db::commit();
        } catch (Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return true;
    }

    public function getUserLists($filter): array
    {
        $result = $this->getRepository()->getLists($filter);
        return $this->_addPowerToUserList($result);
    }

    public function pageUserLists($filter, $columns = '*', $page = 1, $page_size = 20): array
    {
        $result = $this->getRepository()->pageLists($filter, $columns, $page, $page_size);
        $result['list'] = $this->_addPowerToUserList($result['list']);
        return $result;
    }

    /**
     * 获取用户有权限的menu_ids.
     */
    public function getUserAllMenuIds(int $user_id): array
    {
        $powerList = [];
        if (! empty($user_id)) {
            $powerList = $this->getPowerRepository()->getLists([
                'parent_id' => $user_id,
                'parent_type' => SystemPowerRepository::PARENT_TYPE_USER,
            ]);
        }
        $role_ids = [];
        $menu_ids = [];
        foreach ($powerList as $power) {
            if ($power['parent_id'] != $user_id || $power['parent_type'] != SystemPowerRepository::PARENT_TYPE_USER) {
                continue;
            }
            if ($power['children_type'] == SystemPowerRepository::CHILDREN_TYPE_ROLE) {
                $role_ids[] = $power['children_id'];
                continue;
            }
            if ($power['children_type'] == SystemPowerRepository::CHILDREN_TYPE_MENU) {
                $menu_ids[] = $power['children_id'];
            }
        }
        $role_ids = array_values(array_filter(array_unique($role_ids)));
        $powerList = [];
        if (! empty($role_ids)) {
            $powerList = $this->getPowerRepository()->getLists([
                'parent_id' => $role_ids,
                'parent_type' => SystemPowerRepository::PARENT_TYPE_ROLE,
                'children_type' => SystemPowerRepository::CHILDREN_TYPE_MENU,
            ]);
        }
        foreach ($powerList as $power) {
            if (! in_array($power['parent_id'], $role_ids) || $power['parent_type'] != SystemPowerRepository::PARENT_TYPE_ROLE) {
                continue;
            }
            $menu_ids[] = $power['children_id'];
        }

        return array_values(array_filter(array_unique($menu_ids)));
    }

    protected function _addPowerToUserList($userList)
    {
        $user_ids = array_values(array_filter(array_unique(array_column($userList, 'user_id'))));
        $powerList = [];
        if (! empty($user_ids)) {
            $powerList = $this->getPowerRepository()->getLists([
                'parent_id' => $user_ids,
                'parent_type' => SystemPowerRepository::PARENT_TYPE_USER,
            ]);
        }
        foreach ($userList as &$value) {
            $role_ids = [];
            $menu_ids = [];
            foreach ($powerList as $power) {
                if ($power['parent_id'] != $value['user_id']) {
                    continue;
                }
                if ($power['children_type'] == SystemPowerRepository::CHILDREN_TYPE_ROLE) {
                    $role_ids[] = $power['children_id'];
                    continue;
                }
                if ($power['children_type'] == SystemPowerRepository::CHILDREN_TYPE_MENU) {
                    $menu_ids[] = $power['children_id'];
                }
            }
            $value['role_ids'] = array_values(array_filter(array_unique($role_ids)));
            $value['menu_ids'] = array_values(array_filter(array_unique($menu_ids)));
        }

        return $userList;
    }

    protected function _filterInsertList($id, $menu_ids, $role_ids): array
    {
        $insert_list = [];
        foreach ($menu_ids as $menu_id) {
            if (empty($menu_id)) {
                continue;
            }
            $insert_list[] = [
                'parent_id' => $id,
                'parent_type' => SystemPowerRepository::PARENT_TYPE_USER,
                'children_id' => $menu_id,
                'children_type' => SystemPowerRepository::CHILDREN_TYPE_MENU,
            ];
        }
        foreach ($role_ids as $role_id) {
            if (empty($role_id)) {
                continue;
            }
            $insert_list[] = [
                'parent_id' => $id,
                'parent_type' => SystemPowerRepository::PARENT_TYPE_USER,
                'children_id' => $role_id,
                'children_type' => SystemPowerRepository::PARENT_TYPE_ROLE,
            ];
        }

        return $insert_list;
    }
}
