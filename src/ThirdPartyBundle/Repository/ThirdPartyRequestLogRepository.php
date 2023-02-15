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
namespace ThirdPartyBundle\Repository;

use App\Repository\Repository;
use Hyperf\Di\Annotation\Inject;
use ThirdPartyBundle\Model\ThirdPartyRequestLogModel;

class ThirdPartyRequestLogRepository extends Repository
{
    #[Inject]
    protected ThirdPartyRequestLogModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): ThirdPartyRequestLogModel
    {
        return $this->model;
    }

    public function setColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if (in_array($key, ['params', 'result'])) {
                $data[$key] = json_encode($value);
            }
        }
        return $data;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::formatColumnData($data);
        foreach ($data as $key => $value) {
            if (in_array($key, ['params', 'result'])) {
                $data[$key] = ! empty($value) ? @json_decode($value, true) : $value;
            }
        }
        return $data;
    }
}
