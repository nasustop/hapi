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
use Hyperf\HttpMessage\Exception\BadRequestHttpException;

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

    public function updateGoodsSpec($filter, $data): array
    {
        $specInfo = $this->getRepository()->getInfo($filter);
        if (empty($specInfo)) {
            throw new BadRequestHttpException('无效的规格');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateOneBy(['spec_id' => $specInfo['spec_id']], $data);
            $oldSpecValueData = $this->getSpecValueRepository()->getLists(['spec_id' => $specInfo['spec_id']]);
            $oldSpecValueIds = array_column($oldSpecValueData, 'spec_value_id');
            $batchInsertData = [];
            $updateSpecValueIds = [];
            foreach ($data['spec_value'] ?? [] as $value) {
                if (empty($value['spec_value_id'])) {
                    $value['spec_id'] = $specInfo['spec_id'];
                    $batchInsertData[] = $value;
                } else {
                    $updateSpecValueIds[] = $value['spec_value_id'];
                    $this->getSpecValueRepository()->updateOneBy([
                        'spec_value_id' => $value['spec_value_id'],
                    ], [
                        'spec_value_img' => $value['spec_value_img'],
                        'spec_value_name' => $value['spec_value_name'],
                        'sort' => $value['sort'],
                    ]);
                }
            }
            if (! empty($batchInsertData)) {
                $this->getSpecValueRepository()->batchInsert($batchInsertData);
            }
            $deleteSpecValueIds = array_diff($oldSpecValueIds, $updateSpecValueIds);
            if (! empty($deleteSpecValueIds)) {
                $this->getSpecValueRepository()->deleteBy(['spec_value_id' => $deleteSpecValueIds]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $this->getGoodsSpecInfo(['spec_id' => $specInfo['spec_id']]);
    }

    public function getGoodsSpecList(array $filter = [], array|string $columns = '*', int $page = 1, int $pageSize = 100, array $orderBy = []): array
    {
        $result = $this->getRepository()->pageLists($filter, $columns, $page, $pageSize, $orderBy);
        if (empty($result['list'])) {
            return $result;
        }
        $specIds = array_values(array_column($result['list'], 'spec_id'));
        if (empty($specIds)) {
            return $result;
        }
        $specData = array_column($result['list'], null, 'spec_id');
        $specValueData = $this->getSpecValueRepository()->getLists(['spec_id' => $specIds]);
        foreach ($specValueData as $value) {
            $specData[$value['spec_id']]['spec_value'][] = $value;
        }
        $result['list'] = array_values($specData);
        return $result;
    }

    public function getGoodsSpecValueList($spec_value_id): array
    {
        $valueList = $this->getSpecValueRepository()->getLists(['spec_value_id' => $spec_value_id]);
        if (empty($valueList)) {
            return [];
        }
        $spec_ids = array_values(array_filter(array_unique(array_column($valueList, 'spec_id'))));
        if (empty($spec_ids)) {
            return [];
        }
//        return $this->getGoodsSpecList(['spec_id' => $spec_ids], '*', 1, 0);
        $specList = $this->getRepository()->getLists(['spec_id' => $spec_ids]);
        if (empty($specList)) {
            return [];
        }
        $specList = array_column($specList, null, 'spec_id');
        foreach ($valueList as $value) {
            if (! empty($specList[$value['spec_id']])) {
                $specList[$value['spec_id']]['spec_value'][] = $value;
            }
        }
        return array_values($specList);
    }
}
