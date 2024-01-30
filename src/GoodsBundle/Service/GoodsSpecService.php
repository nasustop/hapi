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

namespace GoodsBundle\Service;

use GoodsBundle\Repository\GoodsSpecRepository;
use GoodsBundle\Repository\GoodsSpecValueRepository;
use Hyperf\DbConnection\Db;

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
class GoodsSpecService
{
    protected GoodsSpecRepository $repository;

    protected GoodsSpecValueRepository $specValueRepository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): GoodsSpecRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(GoodsSpecRepository::class);
        }
        return $this->repository;
    }

    /**
     * get GoodsSpecValueRepository.
     */
    public function getSpecValueRepository(): GoodsSpecValueRepository
    {
        if (empty($this->specValueRepository)) {
            $this->specValueRepository = \Hyperf\Support\make(GoodsSpecValueRepository::class);
        }
        return $this->specValueRepository;
    }

    public function getGoodsSpecInfo($filter): array
    {
        $result = $this->getRepository()->getInfo($filter);
        if (! empty($result)) {
            $result['spec_value'] = $this->getSpecValueRepository()
                ->getLists(['spec_id' => $result['spec_id']]);
        }
        return $result;
    }

    /**
     * create GoodsSpec.
     * @param mixed $data
     */
    public function createGoodsSpec($data): array
    {
        Db::beginTransaction();
        try {
            $spec_id = $this->getRepository()->insertGetId($data);
            $batchInsertData = [];
            foreach ($data['spec_value'] ?? [] as $value) {
                $value['spec_id'] = $spec_id;
                $batchInsertData[] = $value;
            }
            if (! empty($batchInsertData)) {
                $this->getSpecValueRepository()->batchInsert($batchInsertData);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $this->getGoodsSpecInfo(['spec_id' => $spec_id]);
    }
}
