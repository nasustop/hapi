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
use SystemBundle\Repository\SystemMenuRepository;
use SystemBundle\Repository\SystemPowerRepository;

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
class SystemMenuService
{
    protected SystemMenuRepository $repository;

    protected SystemPowerRepository $powerRepository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): SystemMenuRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(SystemMenuRepository::class);
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

    public function getInfo(array $filter): array
    {
        $result = $this->getRepository()->getInfo($filter);
        if (! empty($result)) {
            $power = $this->getPowerRepository()->addPowerListByParentIds([$result['menu_id']], SystemPowerRepository::ENUM_PARENT_TYPE_MENU);
            $result['api_ids'] = $power[$result['menu_id']]['api_ids'] ?? [];
        }
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function createMenu(array $data): array
    {
        Db::beginTransaction();
        try {
            $menu_id = $this->getRepository()->insertGetId($data);
            $this->getPowerRepository()->batchInsertByAuth(
                $menu_id,
                SystemPowerRepository::ENUM_PARENT_TYPE_MENU,
                $data['api_ids'],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_API,
            );
            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getInfo(['menu_id' => $menu_id]);
    }

    public function updateMenu(array $filter, array $data)
    {
        $info = $this->getRepository()->getInfo(filter: $filter);
        if (empty($info)) {
            throw new BadRequestHttpException('当前修改的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateOneBy(['menu_id' => $info['menu_id']], $data);
            $this->getPowerRepository()->batchInsertByAuth(
                $info['menu_id'],
                SystemPowerRepository::ENUM_PARENT_TYPE_MENU,
                $data['api_ids'],
                SystemPowerRepository::ENUM_CHILDREN_TYPE_API,
            );
            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return $this->getInfo(['menu_id' => $info['menu_id']]);
    }

    public function deleteMenu(array $filter)
    {
        $info = $this->getRepository()->getInfo(filter: $filter);
        if (empty($info)) {
            throw new BadRequestHttpException('当前删除的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->deleteOneBy(['menu_id' => $info['menu_id']]);
            $this->getPowerRepository()->deleteBy([
                'parent_id' => $info['menu_id'],
                'parent_type' => SystemPowerRepository::ENUM_PARENT_TYPE_MENU,
            ]);
            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }
        return true;
    }
}
