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
            $this->repository = make(SystemRoleRepository::class);
        }
        return $this->repository;
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

    /**
     * 添加角色.
     * @throws \Exception
     */
    public function createRole(array $data): array
    {
        Db::beginTransaction();
        try {
            $role_id = $this->getRepository()->insertGetId(data: $data);

            $this->getPowerRepository()->batchInsertByAuth(
                $role_id,
                SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                $data['menu_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            );

            $this->getPowerRepository()->batchInsertByAuth(
                $role_id,
                SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                $data['api_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_API,
            );

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

            $this->getPowerRepository()->batchInsertByAuth(
                $roleInfo['role_id'],
                SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                $data['menu_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_MENU,
            );
            $this->getPowerRepository()->batchInsertByAuth(
                $roleInfo['role_id'],
                SystemPowerRepository::ENUM_PARENT_TYPE_ROLE,
                $data['api_ids'] ?? [],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_API,
            );

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

    public function getInfo(array $filter): array
    {
        $result = $this->getRepository()->getInfo($filter);
        if (! empty($result)) {
            $powerData = $this->getPowerRepository()->addPowerListByParentIds([$result['role_id']], SystemPowerRepository::ENUM_PARENT_TYPE_ROLE);
            $result['menu_ids'] = $powerData[$result['role_id']]['menu_ids'] ?? [];
            $result['api_ids'] = $powerData[$result['role_id']]['api_ids'] ?? [];
        }
        return $result;
    }

    /**
     * 获取全部列表.
     */
    public function getRoleLists(array $filter): array
    {
        $result = $this->getRepository()->getLists(filter: $filter);
        $parentIds = array_column($result, 'role_id');
        $powerData = $this->getPowerRepository()->addPowerListByParentIds($parentIds, SystemPowerRepository::ENUM_PARENT_TYPE_ROLE);
        foreach ($result as $k => $v) {
            $result[$k]['menu_ids'] = $powerData[$v['role_id']]['menu_ids'] ?? [];
            $result[$k]['api_ids'] = $powerData[$v['role_id']]['api_ids'] ?? [];
        }
        return $result;
    }

    /**
     * 获取分页列表.
     */
    public function pageRoleLists(array $filter, array|string $columns = '*', int $page = 1, int $pageSize = 20): array
    {
        $result = $this->getRepository()->pageLists(filter: $filter, columns: $columns, page: $page, pageSize: $pageSize);

        $parentIds = array_column($result['list'], 'role_id');
        $powerData = $this->getPowerRepository()->addPowerListByParentIds($parentIds, SystemPowerRepository::ENUM_PARENT_TYPE_ROLE);
        foreach ($result['list'] as $k => $v) {
            $result['list'][$k]['menu_ids'] = $powerData[$v['role_id']]['menu_ids'] ?? [];
            $result['list'][$k]['api_ids'] = $powerData[$v['role_id']]['api_ids'] ?? [];
        }
        return $result;
    }
}
