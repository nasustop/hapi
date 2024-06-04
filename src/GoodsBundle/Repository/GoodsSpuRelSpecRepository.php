<?php

declare(strict_types=1);

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsSpuRelSpecModel;

class GoodsSpuRelSpecRepository extends Repository
{
    

    protected GoodsSpuRelSpecModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSpuRelSpecModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSpuRelSpecModel::class);
        }
        return $this->model;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'is_custom_spec':
                case 'is_custom_spec_value':
                case 'is_default_spec_value':
                    $data[$key] = $value === 1;
                    break;
            }
        }
        return $data;
    }
}
