<?php

declare(strict_types=1);

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsParamsValueModel;

class GoodsParamsValueRepository extends Repository
{
    

    protected GoodsParamsValueModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsParamsValueModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsParamsValueModel::class);
        }
        return $this->model;
    }
}
