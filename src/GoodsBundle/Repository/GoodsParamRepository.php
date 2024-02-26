<?php

declare(strict_types=1);

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsParamModel;

class GoodsParamRepository extends Repository
{
    

    protected GoodsParamModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsParamModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsParamModel::class);
        }
        return $this->model;
    }
}
