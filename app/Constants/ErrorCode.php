<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Success.")
     */
    public const SUCCESS = 200;

    /**
     * @Message("Bad Request！")
     */
    public const BAD_REQUEST = 400;

    /**
     * @Message("Unauthorized! ")
     */
    public const UNAUTHORIZED = 401;

    /**
     * @Message("Forbidden! ")
     */
    public const FORBIDDEN = 403;

    /**
     * @Message("Not Found! ")
     */
    public const NOT_FOUND = 404;

    /**
     * @Message("Method Not Allowed! ")
     */
    public const METHOD_NOT_ALLOWED = 405;

    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;
}
