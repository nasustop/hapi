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

namespace SystemBundle\Repository;

use App\Repository\Repository;
use SystemBundle\Model\SystemApiModel;

class SystemApiRepository extends Repository
{
    protected SystemApiModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemApiModel
    {
        if (empty($this->model)) {
            $this->model = make(SystemApiModel::class);
        }
        return $this->model;
    }

    public function setColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'api_method') {
                $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }
        return $data;
    }
}
