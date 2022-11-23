<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
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
    #[Inject]
    protected SystemRoleRepository $repository;

    #[Inject]
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
     * 添加角色.
     * @throws Exception
     */
    public function createRole(array $data): array
    {
        Db::beginTransaction();
        try {
            $role_id = $this->getRepository()->insertGetId($data);

            $insertData = $this->_filterInsertList($role_id, $data['menu_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert($insertData);
            }

            Db::commit();
        } catch (Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getInfo(['role_id' => $role_id]);
    }

    /**
     * 修改角色.
     * @throws Exception
     */
    public function updateRole(array $filter, array $data): array
    {
        $roleInfo = $this->getInfo($filter);
        if (empty($roleInfo)) {
            throw new BadRequestHttpException('当前修改的角色不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateBy(['role_id' => $roleInfo['role_id']], $data);

            $this->getPowerRepository()->deleteBy([
                'parent_id' => $roleInfo['role_id'],
                'parent_type' => SystemPowerRepository::PARENT_TYPE_ROLE,
            ]);

            $insertData = $this->_filterInsertList($roleInfo['role_id'], $data['menu_ids'] ?? []);
            if (! empty($insertData)) {
                $this->getPowerRepository()->batchInsert($insertData);
            }

            Db::commit();
        } catch (Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getInfo(['role_id' => $roleInfo['role_id']]);
    }

    /**
     * 删除角色.
     * @throws Exception
     */
    public function deleteRole(array $filter): bool
    {
        $info = $this->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('删除的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->deleteOneBy(['role_id' => $info['role_id']]);

            $this->getPowerRepository()->deleteBy([
                'parent_id' => $info['role_id'],
                'parent_type' => SystemPowerRepository::PARENT_TYPE_ROLE,
            ]);

            Db::commit();
        } catch (Exception $exception) {
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
        $result = $this->getRepository()->getLists($filter);
        return $this->_addMenuIdsToRoleList($result);
    }

    /**
     * 获取分页列表.
     */
    public function pageRoleLists(array $filter, array|string $columns = '*', int $page = 1, int $page_size = 20): array
    {
        $result = $this->getRepository()->pageLists($filter, $columns, $page, $page_size);
        $result['list'] = $this->_addMenuIdsToRoleList($result['list']);
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
            $powerList = $this->getPowerRepository()->getLists([
                'parent_id' => $role_ids,
                'parent_type' => SystemPowerRepository::PARENT_TYPE_ROLE,
                'children_type' => SystemPowerRepository::CHILDREN_TYPE_MENU,
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
                'parent_type' => SystemPowerRepository::PARENT_TYPE_ROLE,
                'children_id' => $menu_id,
                'children_type' => SystemPowerRepository::CHILDREN_TYPE_MENU,
            ];
        }

        return $insert_list;
    }
}
