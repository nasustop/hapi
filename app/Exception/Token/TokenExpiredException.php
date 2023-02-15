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
namespace App\Exception\Token;

use App\Constants\ErrorCode;
use App\Exception\AbstractErrorCodeException;

class TokenExpiredException extends AbstractErrorCodeException
{
    protected $code = ErrorCode::TOKEN_EXPIRED;
}
