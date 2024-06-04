<?php

declare(strict_types=1);

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsSkuModel;

class GoodsSkuRepository extends Repository
{
    protected GoodsSpuRelSpecRepository $goodsSpuRelSpecRepository;
    protected GoodsSkuRelValueRepository $goodsSkuRelValueRepository;
    
	public const ENUM_STATUS_ON_SALE = 'on_sale';
	public const ENUM_STATUS_OFF_SALE = 'off_sale';
	public const ENUM_STATUS = [self::ENUM_STATUS_ON_SALE => 'on_sale',self::ENUM_STATUS_OFF_SALE => 'off_sale'];
	public const ENUM_STATUS_DEFAULT = self::ENUM_STATUS_ON_SALE;
    public function enumStatus(): array
    {
        return self::ENUM_STATUS;
    }

    public function enumStatusDefault(): string
    {
        return self::ENUM_STATUS_DEFAULT;
    }
    protected GoodsSkuModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSkuModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSkuModel::class);
        }
        return $this->model;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'sku_images':
                    $data[$key] = json_decode($value, true);
                    break;
                case 'open_images':
                case 'open_params':
                case 'open_intro':
                    $data[$key] = $value === 1;
                    break;
            }
        }
        return $data;
    }

    public function setColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'sku_images':
                    $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
                    break;
                case 'sku_volume':
                case 'sku_weight':
                    $data[$key] = floatval($value);
                    break;
            }
        }
        return $data;
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
     * get GoodsSkuRelValueRepository.
     */
    public function getGoodsSkuRelValueRepository(): GoodsSkuRelValueRepository
    {
        if (empty($this->goodsSkuRelValueRepository)) {
            $this->goodsSkuRelValueRepository = \Hyperf\Support\make(GoodsSkuRelValueRepository::class);
        }
        return $this->goodsSkuRelValueRepository;
    }

    public function batchInsertGoodsSku($spu_id, $params)
    {
        if ($params['open_spec']) {
            $specValueData = [];
            foreach ($params['spec_value'] as $value) {
                $value['spu_id'] = $spu_id;
                $specValueData[] = $value;
            }
            $this->getGoodsSpuRelSpecRepository()->batchInsert($specValueData);
            $skuRelValueBatchInsertData = [];
            foreach ($params['sku_value'] as $value) {
                $value['spu_id'] = $spu_id;
                $sku_id = $this->insertGetId($value);
                if (empty($sku_id)) {
                    continue;
                }
                foreach ($value['spec_value_ids'] as $spec_value_id) {
                    $skuRelValueBatchInsertData[] = [
                        'spu_id' => $spu_id,
                        'sku_id' => $sku_id,
                        'rel_type' => GoodsSkuRelValueRepository::ENUM_REL_TYPE_SPEC_VALUE,
                        'rel_id' => $spec_value_id,
                    ];
                }
                if ($value['open_params']) {
                    foreach ($value['params_value'] as $value_id) {
                        $skuRelValueBatchInsertData[] = [
                            'spu_id' => $spu_id,
                            'sku_id' => $sku_id,
                            'rel_type' => GoodsSkuRelValueRepository::ENUM_REL_TYPE_PARAMS,
                            'rel_id' => $value_id,
                        ];
                    }
                }
            }
            if (! empty($skuRelValueBatchInsertData)) {
                $this->getGoodsSkuRelValueRepository()->batchInsert($skuRelValueBatchInsertData);
            }
        } else {
            $aloneSpecData = $params['sku_info'];
            $aloneSpecData['spu_id'] = $spu_id;
            $this->saveData($aloneSpecData);
        }
    }

    public function batchUpdateGoodsSku($spu_id, $params)
    {
        // 删除sku关联数据
        $this->getGoodsSkuRelValueRepository()->deleteBy(['spu_id' => $spu_id]);
        if ($params['open_spec']) {
            $specValueData = [];
            foreach ($params['spec_value'] as $value) {
                $value['spu_id'] = $spu_id;
                $specValueData[] = $value;
            }
            $this->getGoodsSpuRelSpecRepository()->deleteBy(['spu_id' => $spu_id]);
            $this->getGoodsSpuRelSpecRepository()->batchInsert($specValueData);
            $skuRelValueBatchInsertData = [];
            $updateSkuIds = [];
            foreach ($params['sku_value'] as $value) {
                $value['spu_id'] = $spu_id;
                if (! empty($value['sku_id'])) {
                    $this->updateOneBy(['sku_id' => $value['sku_id']], $value);
                    $sku_id = $value['sku_id'];
                } else {
                    $sku_id = $this->insertGetId($value);
                }
                if (empty($sku_id)) {
                    continue;
                }
                $updateSkuIds[] = $sku_id;
                foreach ($value['spec_value_ids'] as $spec_value_id) {
                    $skuRelValueBatchInsertData[] = [
                        'spu_id' => $spu_id,
                        'sku_id' => $sku_id,
                        'rel_type' => GoodsSkuRelValueRepository::ENUM_REL_TYPE_SPEC_VALUE,
                        'rel_id' => $spec_value_id,
                    ];
                }
                if ($value['open_params']) {
                    foreach ($value['params_value'] as $value_id) {
                        $skuRelValueBatchInsertData[] = [
                            'spu_id' => $spu_id,
                            'sku_id' => $sku_id,
                            'rel_type' => GoodsSkuRelValueRepository::ENUM_REL_TYPE_PARAMS,
                            'rel_id' => $value_id,
                        ];
                    }
                }
            }
            if (! empty($skuRelValueBatchInsertData)) {
                $this->getGoodsSkuRelValueRepository()->batchInsert($skuRelValueBatchInsertData);
            }
            if (! empty($updateSkuIds)) {
                $this->deleteBy([
                    'spu_id' => $spu_id,
                    'sku_id|notIn' => $updateSkuIds,
                ]);
            }
        } else {
            // 单规格sku数据
            $aloneSpecData = $params['sku_info'];
            $aloneSpecData['spu_id'] = $spu_id;
            if (! empty($aloneSpecData['sku_id'])) {
                $this->updateOneBy(['sku_id' => $aloneSpecData['sku_id']], $aloneSpecData);
            } else {
                $this->deleteBy(['spu_id' => $spu_id]);
                $this->saveData($aloneSpecData);
            }
        }

    }
}
