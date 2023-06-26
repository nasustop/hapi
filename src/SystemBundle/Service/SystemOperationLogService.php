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

use SystemBundle\Repository\SystemOperationLogRepository;

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
class SystemOperationLogService
{
    protected SystemOperationLogRepository $repository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): SystemOperationLogRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(SystemOperationLogRepository::class);
        }
        return $this->repository;
    }
}
