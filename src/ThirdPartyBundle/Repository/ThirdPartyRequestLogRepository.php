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
    public const ENUM_METHOD_GET = 'GET';

    public const ENUM_METHOD_POST = 'POST';

    public const ENUM_METHOD_PUT = 'PUT';

    public const ENUM_METHOD_DELETE = 'DELETE';

    public const ENUM_METHOD_HEAD = 'HEAD';

    public const ENUM_METHOD = [self::ENUM_METHOD_GET => 'GET', self::ENUM_METHOD_POST => 'POST', self::ENUM_METHOD_PUT => 'PUT', self::ENUM_METHOD_DELETE => 'DELETE', self::ENUM_METHOD_HEAD => 'HEAD'];

    public const ENUM_METHOD_DEFAULT = self::ENUM_METHOD_GET;

    public const ENUM_STATUS_SUCCESS = 'success';

    public const ENUM_STATUS_FAIL = 'fail';

    public const ENUM_STATUS = [self::ENUM_STATUS_SUCCESS => 'success', self::ENUM_STATUS_FAIL => 'fail'];

    public const ENUM_STATUS_DEFAULT = self::ENUM_STATUS_SUCCESS;

    #[Inject]
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

    public function enumMethod(): array
    {
        return self::ENUM_METHOD;
    }

    public function enumMethodDefault(): string
    {
        return self::ENUM_METHOD_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): ThirdPartyRequestLogModel
    {
        return $this->model;
    }
}
