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
}
