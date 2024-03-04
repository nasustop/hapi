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

use GoodsBundle\Repository\GoodsCategoryRepository;
use GoodsBundle\Repository\GoodsParamsRepository;
use GoodsBundle\Repository\GoodsParamsValueRepository;
use GoodsBundle\Repository\GoodsSpuRelValueRepository;
use GoodsBundle\Repository\GoodsSpuRepository;
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
class GoodsSpuService
{
    protected GoodsSpuRepository $repository;

    protected GoodsSpuRelValueRepository $goodsSpuRelValueRepository;

    protected GoodsCategoryRepository $goodsCategoryRepository;

    protected GoodsParamsRepository $goodsParamRepository;

    protected GoodsParamsValueRepository $goodsParamsValueRepository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): GoodsSpuRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(GoodsSpuRepository::class);
        }
        return $this->repository;
    }

    /**
     * get GoodsSpuRelValueRepository.
     */
    public function getGoodsSpuRelValueRepository()
    {
        if (empty($this->goodsSpuRelValueRepository)) {
            $this->goodsSpuRelValueRepository = make(GoodsSpuRelValueRepository::class);
        }
        return $this->goodsSpuRelValueRepository;
    }

    /**
     * get GoodsCategoryRepository.
     */
    public function getGoodsCategoryRepository(): GoodsCategoryRepository
    {
        if (empty($this->goodsCategoryRepository)) {
            $this->goodsCategoryRepository = make(GoodsCategoryRepository::class);
        }
        return $this->goodsCategoryRepository;
    }

    /**
     * get GoodsParamRepository.
     */
    public function getGoodsParamRepository(): GoodsParamsRepository
    {
        if (empty($this->goodsParamRepository)) {
            $this->goodsParamRepository = make(GoodsParamsRepository::class);
        }
        return $this->goodsParamRepository;
    }

    /**
     * get GoodsParamsValueRepository.
     */
    public function getGoodsParamsValueRepository(): GoodsParamsValueRepository
    {
        if (empty($this->goodsParamsValueRepository)) {
            $this->goodsParamsValueRepository = make(GoodsParamsValueRepository::class);
        }
        return $this->goodsParamsValueRepository;
    }

    public function getGoodsSpuInfo($filter): array
    {
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            return $info;
        }
        $assembleData = $this->assembleSpuRelData([$info['spu_id']]);
        $info['category_ids'] = $assembleData[$info['spu_id']]['category_ids'] ?? [];
        $info['params_value'] = $assembleData[$info['spu_id']]['params_value'] ?? [];
        $info['params_value_ids'] = $assembleData[$info['spu_id']]['params_value_ids'] ?? [];
        $info['params_value_data'] = $assembleData[$info['spu_id']]['params_value_data'] ?? [];
        return $info;
    }

    public function assembleSpuRelData($spuIds)
    {
        $result = [];
        $relData = $this->getGoodsSpuRelValueRepository()
            ->getLists(['spu_id' => $spuIds]);
        $brandRelIds = [];
        $categoryRelIds = [];
        $paramsRelIds = [];
        foreach ($relData as $value) {
            switch ($value['rel_type']) {
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_BRAND:
                    $brandRelIds[] = $value['rel_id'];
                    break;
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY:
                    $categoryRelIds[] = $value['rel_id'];
                    break;
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS:
                    $paramsRelIds[] = $value['rel_id'];
                    break;
            }
        }
        $brandRelIds = array_values(array_filter(array_unique($brandRelIds)));
        $categoryRelIds = array_values(array_filter(array_unique($categoryRelIds)));
        $paramsRelIds = array_values(array_filter(array_unique($paramsRelIds)));
        $categoryAssembleData = [];
        if (! empty($categoryRelIds)) {
            $categoryRelData = $this->getGoodsCategoryRepository()->getCategoryCascadeParentIds($categoryRelIds);
            foreach ($categoryRelData as $value) {
                $categoryAssembleData[$value['category_id']] = $value['parent_data'];
            }
        }
        $paramsData = [];
        $paramsValueData = [];
        if (! empty($paramsRelIds)) {
            $paramsValueData = $this->getGoodsParamsValueRepository()->getLists(['params_value_id' => $paramsRelIds]);

            $paramsIds = array_values(array_unique(array_filter(array_column($paramsValueData, 'params_id'))));
            if (! empty($paramsIds)) {
                $paramsData = $this->getGoodsParamRepository()->getLists(['params_id' => $paramsIds]);
                $paramsData = array_column($paramsData, null, 'params_id');
                $valueData = $this->getGoodsParamsValueRepository()->getLists(['params_id' => $paramsIds]);
                foreach ($paramsData as $k => $v) {
                    $paramsData[$k]['params_value'] = [];
                    foreach ($valueData as $value) {
                        if ($v['params_id'] === $value['params_id']) {
                            $paramsData[$k]['params_value'][] = $value;
                        }
                    }
                }
            }
        }
        foreach ($relData as $value) {
            switch ($value['rel_type']) {
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_BRAND:
                    break;
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY:
                    if (! empty($categoryAssembleData[$value['rel_id']])) {
                        $result[$value['spu_id']]['category_ids'][] = $categoryAssembleData[$value['rel_id']];
                    }
                    break;
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS:
                    foreach ($paramsValueData as $paramsValueItem) {
                        if ($value['rel_id'] != $paramsValueItem['params_value_id']) {
                            continue;
                        }
                        $result[$value['spu_id']]['params_value'][$paramsValueItem['params_id']] = $paramsValueItem['params_value_id'];
                        $result[$value['spu_id']]['params_value_ids'][] = $paramsValueItem['params_id'];
                        $result[$value['spu_id']]['params_value_data'][$paramsValueItem['params_id']] = $paramsData[$paramsValueItem['params_id']] ?? [];
                    }
                    break;
            }
        }
        foreach ($result as $spu_id => $value) {
            $result[$spu_id]['params_value_ids'] = array_values(array_unique(array_filter($value['params_value_ids'])));
            $result[$spu_id]['params_value_data'] = array_values(array_unique(array_filter($value['params_value_data'])));
        }
        return $result;
    }

    public function getGoodsSpuList($filter, $columns = '*', $page = 1, $page_size = 20, $orderBy = [])
    {
        $result = $this->getRepository()->pageListsByJoin($filter, $columns, $page, $page_size, $orderBy);
        if (empty($result['list'])) {
            return $result;
        }
        $spuIds = array_column($result['list'], 'spu_id');
        $assembleData = $this->assembleSpuRelData($spuIds);
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['category_ids'] = $assembleData[$value['spu_id']]['category_ids'] ?? [];
            $result['list'][$key]['params_value'] = $assembleData[$value['spu_id']]['params_value'] ?? [];
            $result['list'][$key]['params_value_ids'] = $assembleData[$value['spu_id']]['params_value_ids'] ?? [];
            $result['list'][$key]['params_value_data'] = $assembleData[$value['spu_id']]['params_value_data'] ?? [];
        }
        return $result;
    }

    public function createGoodsSpu($params): array
    {
        Db::beginTransaction();
        try {
            $spu_id = $this->getRepository()->insertGetId($params);
            $batchInsertData = [];
            foreach ($params['category_ids'] ?? [] as $value) {
                if (empty($value)) {
                    continue;
                }
                $batchInsertData[] = [
                    'spu_id' => $spu_id,
                    'rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY,
                    'rel_id' => array_pop($value),
                ];
            }
            foreach ($params['params_value'] ?? [] as $value) {
                if (empty($value)) {
                    continue;
                }
                $batchInsertData[] = [
                    'spu_id' => $spu_id,
                    'rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS,
                    'rel_id' => $value,
                ];
            }
            if (! empty($batchInsertData)) {
                $this->getGoodsSpuRelValueRepository()->batchInsert($batchInsertData);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $this->getRepository()->getInfo(['spu_id' => $spu_id]);
    }

    public function updateGoodsSpu($filter, $params): array
    {
        var_dump($params);
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('修改的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateOneBy(['spu_id' => $info['spu_id']], $params);
            $oldRelData = $this->getGoodsSpuRelValueRepository()->getLists(['spu_id' => $info['spu_id']]);
            $oldRelCategoryIds = [];
            $updateRelCategoryIds = [];
            $oldRelParamsIds = [];
            $updateRelParamsIds = [];
            $batchInsertData = [];
            foreach ($oldRelData as $value) {
                switch ($value['rel_type']) {
                    case GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY:
                        $oldRelCategoryIds[] = $value['rel_id'];
                        break;
                    case GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS:
                        $oldRelParamsIds[] = $value['rel_id'];
                        break;
                }
            }
            foreach ($params['category_ids'] ?? [] as $value) {
                if (empty($value)) {
                    continue;
                }
                $rel_id = array_pop($value);
                if (! in_array($rel_id, $oldRelCategoryIds)) {
                    $batchInsertData[] = [
                        'spu_id' => $info['spu_id'],
                        'rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY,
                        'rel_id' => $rel_id,
                    ];
                } else {
                    $updateRelCategoryIds[] = $rel_id;
                }
            }
            foreach ($params['params_value'] ?? [] as $value) {
                if (empty($value)) {
                    continue;
                }
                if (! in_array($value, $oldRelParamsIds)) {
                    $batchInsertData[] = [
                        'spu_id' => $info['spu_id'],
                        'rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS,
                        'rel_id' => $value,
                    ];
                } else {
                    $updateRelParamsIds[] = $value;
                }
            }
            if (! empty($batchInsertData)) {
                $this->getGoodsSpuRelValueRepository()->batchInsert($batchInsertData);
            }
            $deleteRelCategoryIds = array_diff($oldRelCategoryIds, $updateRelCategoryIds);
            if (! empty($deleteRelCategoryIds)) {
                $this->getGoodsSpuRelValueRepository()->deleteBy([
                    'spu_id' => $info['spu_id'],
                    'rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY,
                    'rel_id' => $deleteRelCategoryIds,
                ]);
            }
            $deleteRelParamsIds = array_diff($oldRelParamsIds, $updateRelParamsIds);
            if (! empty($deleteRelParamsIds)) {
                $this->getGoodsSpuRelValueRepository()->deleteBy([
                    'spu_id' => $info['spu_id'],
                    'rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS,
                    'rel_id' => $deleteRelParamsIds,
                ]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $info;
    }
}
