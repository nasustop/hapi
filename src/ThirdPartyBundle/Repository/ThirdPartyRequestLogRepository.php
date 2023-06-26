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
use ThirdPartyBundle\Model\ThirdPartyRequestLogModel;

class ThirdPartyRequestLogRepository extends Repository
{
    public const ENUM_METHOD_GET = 'GET';

    public const ENUM_METHOD_POST = 'POST';

    public const ENUM_METHOD_PUT = 'PUT';

    public const ENUM_METHOD_DELETE = 'DELETE';

    public const ENUM_METHOD_HEAD = 'HEAD';

    public const ENUM_METHOD = [self::ENUM_METHOD_GET => 'GET', self::ENUM_METHOD_POST => 'POST', self::ENUM_METHOD_PUT => 'PUT', self::ENUM_METHOD_DELETE => 'DELETE', self::ENUM_METHOD_HEAD => 'HEAD'];

    public const ENUM_METHOD_DEFAULT = '';

    public const ENUM_STATUS_SUCCESS = 'success';

    public const ENUM_STATUS_FAIL = 'fail';

    public const ENUM_STATUS = [self::ENUM_STATUS_SUCCESS => 'success', self::ENUM_STATUS_FAIL => 'fail'];

    public const ENUM_STATUS_DEFAULT = '';

    public const ENUM_STATUS_CODE = [
        200 => 200,
        400 => 400,
        401 => 401,
        403 => 403,
        404 => 404,
        500 => 500,
    ];

    public const ENUM_STATUS_CODE_DEFAULT = '';

    protected ThirdPartyRequestLogModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumMethod(): array
    {
        return self::ENUM_METHOD;
    }

    public function enumMethodDefault(): string
    {
        return self::ENUM_METHOD_DEFAULT;
    }

    public function enumStatus(): array
    {
        return self::ENUM_STATUS;
    }

    public function enumStatusDefault(): string
    {
        return self::ENUM_STATUS_DEFAULT;
    }

    public function enumStatusCode(): array
    {
        return self::ENUM_STATUS_CODE;
    }

    public function enumStatusCodeDefault(): string
    {
        return self::ENUM_STATUS_CODE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): ThirdPartyRequestLogModel
    {
        if (empty($this->model)) {
            $this->model = make(ThirdPartyRequestLogModel::class);
        }
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
