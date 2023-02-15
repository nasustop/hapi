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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server exception or undefined error")
     */
    public const SERVER_ERROR = -1;

    /**
     * @Message("Success!")
     */
    public const SUCCESS = 0;

    /**
     * @Message("token expired!")
     */
    public const TOKEN_EXPIRED = 40101;

    /**
     * @Message("token error!")
     */
    public const TOKEN_ERROR = 40102;

    /**
     * @Message("The current operation is prohibited!")
     */
    public const TOKEN_FORBIDDEN = 40103;
}
