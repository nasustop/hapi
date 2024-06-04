<?php

declare(strict_types=1);

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsSkuRelValueModel;

class GoodsSkuRelValueRepository extends Repository
{
    
	public const ENUM_REL_TYPE_PARAMS = 'params';
	public const ENUM_REL_TYPE_SPEC_VALUE = 'spec_value';
	public const ENUM_REL_TYPE = [self::ENUM_REL_TYPE_PARAMS => 'params',self::ENUM_REL_TYPE_SPEC_VALUE => 'spec_value'];
	public const ENUM_REL_TYPE_DEFAULT = self::ENUM_REL_TYPE_PARAMS;
    public function enumRelType(): array
    {
        return self::ENUM_REL_TYPE;
    }

    public function enumRelTypeDefault(): string
    {
        return self::ENUM_REL_TYPE_DEFAULT;
    }
    protected GoodsSkuRelValueModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSkuRelValueModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSkuRelValueModel::class);
        }
        return $this->model;
    }
}
