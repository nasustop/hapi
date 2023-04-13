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
use SystemBundle\Repository\SystemRoleRepository;

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
class SystemRoleService
{
    protected SystemRoleRepository $repository;

    protected SystemPowerRepository $powerRepository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): SystemRoleRepository
    {
        if (empty($this->repository)) {
            $this->repository = container()->get(SystemRoleRepository::class);
        }
        return $this->repository;
    }

    /**
     * get PowerRepository.
     */
    public function getPowerRepository(): SystemPowerRepository
    {
        if (empty($this->powerRepository)) {
            $this->powerRepository = container()->get(SystemPowerRepository::class);
        }
        return $this->powerRepository;
    }

    /**
     * 添加角色.
     * @throws \Exception
     */
    public function createRole(array $data): array
    {
        Db::beginTransaction();
        try {
            $role_id = $this->getRepository()->insertGetId(data: $data);

            $insertData = $this->_filterInsertList(id: $role_id, menu_ids: $data['menu_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert(data: $insertData);
            }

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getInfo(filter: ['role_id' => $role_id]);
    }

    /**
     * 修改角色.
     * @throws \Exception
     */
    public function updateRole(array $filter, array $data): array
    {
        $roleInfo = $this->getInfo(filter: $filter);
        if (empty($roleInfo)) {
            throw new BadRequestHttpException('当前修改的角色不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateBy(filter: ['role_id' => $roleInfo['role_id']], data: $data);

            $this->getPowerRepository()->deleteBy(filter: [
                'parent_id' => $roleInfo['role_id'],
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
            ]);

            $insertData = $this->_filterInsertList(id: $roleInfo['role_id'], menu_ids: $data['menu_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert(data: $insertData);
            }

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getInfo(filter: ['role_id' => $roleInfo['role_id']]);
    }

    /**
     * 删除角色.
     * @throws \Exception
     */
    public function deleteRole(array $filter): bool
    {
        $info = $this->getInfo(filter: $filter);
        if (empty($info)) {
            throw new BadRequestHttpException('删除的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->deleteOneBy(filter: ['role_id' => $info['role_id']]);

            $this->getPowerRepository()->deleteBy(filter: [
                'parent_id' => $info['role_id'],
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
            ]);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return true;
    }

    /**
     * 获取全部列表.
     */
    public function getRoleLists(array $filter): array
    {
        $result = $this->getRepository()->getLists(filter: $filter);
        return $this->_addMenuIdsToRoleList(roleList: $result);
    }

    /**
     * 获取分页列表.
     */
    public function pageRoleLists(array $filter, array|string $columns = '*', int $page = 1, int $pageSize = 20): array
    {
        $result = $this->getRepository()->pageLists(filter: $filter, columns: $columns, page: $page, pageSize: $pageSize);
        $result['list'] = $this->_addMenuIdsToRoleList(roleList: $result['list']);
        return $result;
    }

    /**
     * 给角色列表添加menu_ids.
     */
    protected function _addMenuIdsToRoleList(array $roleList): array
    {
        $role_ids = array_values(array_filter(array_unique(array_column($roleList, 'role_id'))));
        $powerList = [];
        if (! empty($role_ids)) {
            $powerList = $this->getPowerRepository()->getLists(filter: [
                'parent_id' => $role_ids,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                'children_type' => SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            ]);
        }
        foreach ($roleList as &$value) {
            $menu_ids = [];
            foreach ($powerList as $power) {
                if ($power['parent_id'] != $value['role_id']) {
                    continue;
                }
                $menu_ids[] = $power['children_id'];
            }
            $value['menu_ids'] = array_values(array_filter(array_unique($menu_ids)));
        }

        return $roleList;
    }

    /**
     * 添加角色时格式化要填充的power数据.
     */
    protected function _filterInsertList(int $id, array $menu_ids): array
    {
        $insert_list = [];
        foreach ($menu_ids as $menu_id) {
            if (empty($menu_id)) {
                continue;
            }
            $insert_list[] = [
                'parent_id' => $id,
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                'children_id' => $menu_id,
                'children_type' => SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            ];
        }

        return $insert_list;
    }
}
