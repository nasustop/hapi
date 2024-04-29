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
use GoodsBundle\Repository\GoodsParamsValueRepository;
use GoodsBundle\Repository\GoodsSkuRelValueRepository;
use GoodsBundle\Repository\GoodsSkuRepository;
use GoodsBundle\Repository\GoodsSpecRepository;
use GoodsBundle\Repository\GoodsSpecValueRepository;
use GoodsBundle\Repository\GoodsSpuRelSpecRepository;
use GoodsBundle\Repository\GoodsSpuRelValueRepository;
use GoodsBundle\Repository\GoodsSpuRepository;
use Hyperf\DbConnection\Db;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Rule;

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

    protected GoodsSpuRelSpecRepository $goodsSpuRelSpecRepository;

    protected GoodsSkuRepository $goodsSkuRepository;

    protected GoodsSkuRelValueRepository $goodsSkuRelValueRepository;

    protected GoodsCategoryRepository $goodsCategoryRepository;

    protected GoodsParamsValueRepository $goodsParamsValueRepository;

    protected GoodsSpecRepository $goodsSpecRepository;

    protected GoodsSpecValueRepository $goodsSpecValueRepository;

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
            $this->repository = \Hyperf\Support\make(GoodsSpuRepository::class);
        }
        return $this->repository;
    }

    /**
     * get GoodsSpuRelValueRepository.
     */
    public function getGoodsSpuRelValueRepository()
    {
        if (empty($this->goodsSpuRelValueRepository)) {
            $this->goodsSpuRelValueRepository = \Hyperf\Support\make(GoodsSpuRelValueRepository::class);
        }
        return $this->goodsSpuRelValueRepository;
    }

    /**
     * get GoodsSpuRelSpecRepository.
     */
    public function getGoodsSpuRelSpecRepository(): GoodsSpuRelSpecRepository
    {
        if (empty($this->goodsSpuRelSpecRepository)) {
            $this->goodsSpuRelSpecRepository = \Hyperf\Support\make(GoodsSpuRelSpecRepository::class);
        }
        return $this->goodsSpuRelSpecRepository;
    }

    /**
     * get GoodsSkuRepository.
     */
    public function getGoodsSkuRepository(): GoodsSkuRepository
    {
        if (empty($this->goodsSkuRepository)) {
            $this->goodsSkuRepository = \Hyperf\Support\make(GoodsSkuRepository::class);
        }
        return $this->goodsSkuRepository;
    }

    /**
     * get GoodsSkuRelValueRepository.
     */
    public function getGoodsSkuRelValueRepository(): GoodsSkuRelValueRepository
    {
        if (empty($this->goodsSkuRelValueRepository)) {
            $this->goodsSkuRelValueRepository = \Hyperf\Support\make(GoodsSkuRelValueRepository::class);
        }
        return $this->goodsSkuRelValueRepository;
    }

    /**
     * get GoodsCategoryRepository.
     */
    public function getGoodsCategoryRepository(): GoodsCategoryRepository
    {
        if (empty($this->goodsCategoryRepository)) {
            $this->goodsCategoryRepository = \Hyperf\Support\make(GoodsCategoryRepository::class);
        }
        return $this->goodsCategoryRepository;
    }

    /**
     * get GoodsParamsValueRepository.
     */
    public function getGoodsParamsValueRepository(): GoodsParamsValueRepository
    {
        if (empty($this->goodsParamsValueRepository)) {
            $this->goodsParamsValueRepository = \Hyperf\Support\make(GoodsParamsValueRepository::class);
        }
        return $this->goodsParamsValueRepository;
    }

    /**
     * get GoodsSpecRepository.
     */
    public function getGoodsSpecRepository(): GoodsSpecRepository
    {
        if (empty($this->goodsSpecRepository)) {
            $this->goodsSpecRepository = \Hyperf\Support\make(GoodsSpecRepository::class);
        }
        return $this->goodsSpecRepository;
    }

    /**
     * get SpecValueRepository.
     */
    public function getGoodsSpecValueRepository(): GoodsSpecValueRepository
    {
        if (empty($this->goodsSpecValueRepository)) {
            $this->goodsSpecValueRepository = \Hyperf\Support\make(GoodsSpecValueRepository::class);
        }
        return $this->goodsSpecValueRepository;
    }

    public function createGoodsSpu($params): array
    {
        Db::beginTransaction();
        try {
            $spu_id = $this->getRepository()->insertGetId($params);
            // 添加spu关联参数【品牌，分类，商品参数等】
            $this->getGoodsSpuRelValueRepository()->createSpuRelValue($spu_id, $params);
            // 添加sku
            $this->getGoodsSkuRepository()->batchInsertGoodsSku($spu_id, $params);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $this->getRepository()->getInfo(['spu_id' => $spu_id]);
    }

    public function updateGoodsSpu($filter, $params): array
    {
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('修改的数据不存在');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateOneBy(['spu_id' => $info['spu_id']], $params);
            // 修改spu关联参数
            $this->getGoodsSpuRelValueRepository()->updateSpuRelValue($info['spu_id'], $params);
            // 修改sku
            $this->getGoodsSkuRepository()->batchUpdateGoodsSku($info['spu_id'], $params);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $info;
    }

    public function getGoodsSpuInfo($filter)
    {
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            return [];
        }
        // spu关联数据
        $info = $this->formatSpuRelValueData($info);
        // sku列表
        $info = $this->formatSpuRelSkuData($info);
        return $info;
    }

    /**
     * 格式化spu关联参数数据.
     */
    public function formatSpuRelValueData(array $spuInfo): array
    {
        $spuRelValueList = $this->getGoodsSpuRelValueRepository()->getLists(['spu_id' => $spuInfo['spu_id']]);
        $spuInfo['brand_id'] = 0;
        $spuInfo['category_ids'] = [];
        $spuInfo['params_value'] = [];
        $categoryIds = [];
        $paramsValueIds = [];
        foreach ($spuRelValueList as $value) {
            switch ($value['rel_type']) {
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_BRAND:
                    $spuInfo['brand_id'] = $value['rel_id'];
                    break;
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY:
                    $categoryIds[] = [$value['rel_id']];
                    break;
                case GoodsSpuRelValueRepository::ENUM_REL_TYPE_PARAMS:
                    $paramsValueIds[] = $value['rel_id'];
                    break;
            }
        }
        if (! empty($categoryIds)) {
            // 获取分类的上级分类数组
            $spuInfo['category_ids'] = $this->getGoodsCategoryRepository()->getParentIdsByCategoryIds($categoryIds);
        }
        if ($spuInfo['open_params'] && ! empty($paramsValueIds)) {
            $paramsValueList = $this->getGoodsParamsValueRepository()->getLists(['params_value_id' => $paramsValueIds]);
            foreach ($paramsValueList as $value) {
                $spuInfo['params_value'][$value['params_id']] = $value['params_value_id'];
            }
        }
        return $spuInfo;
    }

    public function formatSpuRelSkuData(array $spuInfo): array
    {
        $spuInfo['sku_info'] = [];
        $spuInfo['spec_value_ids'] = [];
        $spuInfo['spec_value'] = [];
        $spuInfo['sku_value'] = [];
        if (! $spuInfo['open_spec']) {
            // 获取单规格详情
            $spuInfo['sku_info'] = $this->getGoodsSkuRepository()->getInfo(['spu_id' => $spuInfo['spu_id']]);
            return $spuInfo;
        }

        // 格式化sku的spec_value数据
        $spuRelSpecDataOrderBy = [
            'spec_sort' => 'desc',
            'spec_value_sort' => 'desc',
        ];
        // 获取spu关联的规格数据
        $spuRelSpecData = $this->getGoodsSpuRelSpecRepository()->getLists(['spu_id' => $spuInfo['spu_id']], '*', 1, -1, $spuRelSpecDataOrderBy);
        $spuRelSpecIds = array_values(array_filter(array_unique(array_column($spuRelSpecData, 'spec_id'))));
        $spuRelSpecValueIds = array_values(array_filter(array_unique(array_column($spuRelSpecData, 'spec_value_id'))));
        $spuInfo['spec_value_ids'] = $spuRelSpecValueIds;
        $spuRelSpecList = []; // spu关联规格列表
        $spuRelSpecValueList = []; // spu关联规格值列表
        if (! empty($spuRelSpecIds)) {
            // 获取spu关联规格列表
            $spuRelSpecList = $this->getGoodsSpecRepository()->getLists(['spec_id' => $spuRelSpecIds]);
            $spuRelSpecList = array_column($spuRelSpecList, null, 'spec_id');
        }
        if (! empty($spuRelSpecValueIds)) {
            // 获取spu关联规格值列表
            $spuRelSpecValueList = $this->getGoodsSpecValueRepository()->getLists(['spec_value_id' => $spuRelSpecValueIds]);
            $spuRelSpecValueList = array_column($spuRelSpecValueList, null, 'spec_value_id');
        }
        foreach ($spuRelSpecData as $key => $value) {
            $spuRelSpecData[$key]['show_type'] = $spuRelSpecList[$value['spec_id']]['show_type'] ?? '';
            if (!$value['is_custom_spec']) {
                $spuRelSpecData[$key]['spec_name'] = $spuRelSpecList[$value['spec_id']]['spec_name'] ?? $value['spec_name'];
            }
            if (!$value['is_custom_spec_value']) {
                $spuRelSpecData[$key]['spec_value_name'] = $spuRelSpecValueList[$value['spec_value_id']]['spec_value_name'] ?? $value['spec_value_name'];
                $spuRelSpecData[$key]['spec_value_img'] = $spuRelSpecValueList[$value['spec_value_id']]['spec_value_img'] ?? $value['spec_value_img'];
            }
        }
        $spuInfo['spec_value'] = $spuRelSpecData;

        // 获取sku的关联数据
        $skuList = $this->getGoodsSkuRepository()->getLists(['spu_id' => $spuInfo['spu_id']]);
        $skuRelValueData = $this->getGoodsSkuRelValueRepository()->getLists(['spu_id' => $spuInfo['spu_id']]);
        $skuRelParamsValueData = []; // 每个sku关联的商品参数
        $allSkuRelParamsValueIds = []; // 所有sku关联的商品参数
        $skuRelSpecValueData = []; // 每个sku关联的规格值数据
        foreach ($skuRelValueData as $value) {
            switch ($value['rel_type']) {
                case GoodsSkuRelValueRepository::ENUM_REL_TYPE_PARAMS:
                    $allSkuRelParamsValueIds[] = $value['rel_id'];
                    $skuRelParamsValueData[$value['sku_id']][] = $value['rel_id'];
                    break;
                case GoodsSkuRelValueRepository::ENUM_REL_TYPE_SPEC_VALUE:
                    $skuRelSpecValueData[$value['sku_id']][] = $value['rel_id'];
                    break;
            }
        }
        // 获取所有sku关联的商品参数数据
        $paramsValueList = $this->getGoodsParamsValueRepository()->getLists(['params_value_id' => $allSkuRelParamsValueIds]);
        $paramsValueList = array_column($paramsValueList, null, 'params_value_id');
        foreach ($skuList as $key => $value) {
            // 格式换sku的params_value数据
            foreach ($paramsValueList as $item) {
                if (in_array($item['params_value_id'], $skuRelParamsValueData[$value['sku_id']] ?? [])) {
                    $skuList[$key]['params_value'][$item['params_id']] = $item['params_value_id'];
                }
            }
            // 格式化sku的spec相关数据
            $skuList[$key]['spec_value_ids'] = $skuRelSpecValueData[$value['sku_id']] ?? [];
            sort($skuList[$key]['spec_value_ids']);
            $skuList[$key]['spec_value_key'] = implode('-', $skuList[$key]['spec_value_ids']);
        }
        $spuInfo['sku_value'] = $skuList;
        return $spuInfo;
    }
}
