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

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsSpuRelValueModel;

class GoodsSpuRelValueRepository extends Repository
{
    public const ENUM_REL_TYPE_BRAND = 'brand';

    public const ENUM_REL_TYPE_CATEGORY = 'category';

    public const ENUM_REL_TYPE_PARAMS = 'params';

    public const ENUM_REL_TYPE = [self::ENUM_REL_TYPE_BRAND => 'brand', self::ENUM_REL_TYPE_CATEGORY => 'category', self::ENUM_REL_TYPE_PARAMS => 'params'];

    public const ENUM_REL_TYPE_DEFAULT = self::ENUM_REL_TYPE_BRAND;

    protected GoodsSpuRelValueModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumRelType(): array
    {
        return self::ENUM_REL_TYPE;
    }

    public function enumRelTypeDefault(): string
    {
        return self::ENUM_REL_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSpuRelValueModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSpuRelValueModel::class);
        }
        return $this->model;
    }

    public function createSpuRelValue($spu_id, $params)
    {
        $batchInsertData = [];
        if (! empty($params['brand_id'])) {
            // 添加商品品牌
            $batchInsertData[] = [
                'spu_id' => $spu_id,
                'rel_type' => self::ENUM_REL_TYPE_BRAND,
                'rel_id' => $params['brand_id'],
            ];
        }
        // 添加商品分类
        foreach ($params['category_ids'] ?? [] as $value) {
            if (empty($value)) {
                continue;
            }
            $batchInsertData[] = [
                'spu_id' => $spu_id,
                'rel_type' => self::ENUM_REL_TYPE_CATEGORY,
                'rel_id' => array_pop($value),
            ];
        }
        if ($params['open_params']) {
            // 添加商品参数
            foreach ($params['params_value'] ?? [] as $value) {
                if (empty($value)) {
                    continue;
                }
                $batchInsertData[] = [
                    'spu_id' => $spu_id,
                    'rel_type' => self::ENUM_REL_TYPE_PARAMS,
                    'rel_id' => $value,
                ];
            }
        }
        if (! empty($batchInsertData)) {
            $this->batchInsert($batchInsertData);
        }
    }

    public function updateSpuRelValue($spu_id, $params)
    {
        $this->deleteBy([
            'spu_id' => $spu_id,
        ]);
        $this->createSpuRelValue($spu_id, $params);
    }
}
