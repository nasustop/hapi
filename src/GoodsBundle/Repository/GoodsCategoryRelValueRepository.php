<?php

declare(strict_types=1);

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsCategoryRelValueModel;

class GoodsCategoryRelValueRepository extends Repository
{
    
	public const ENUM_REL_TYPE_TYPE_SPEC = 'spec';
	public const ENUM_REL_TYPE_TYPE_PARAMS = 'params';
	public const ENUM_REL_TYPE_TYPE = [self::ENUM_REL_TYPE_TYPE_SPEC => 'spec',self::ENUM_REL_TYPE_TYPE_PARAMS => 'params'];
	public const ENUM_REL_TYPE_TYPE_DEFAULT = self::ENUM_REL_TYPE_TYPE_SPEC;
    public function enumRelTypeType(): array
    {
        return self::ENUM_REL_TYPE_TYPE;
    }

    public function enumRelTypeTypeDefault(): string
    {
        return self::ENUM_REL_TYPE_TYPE_DEFAULT;
    }
    protected GoodsCategoryRelValueModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsCategoryRelValueModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsCategoryRelValueModel::class);
        }
        return $this->model;
    }
}
