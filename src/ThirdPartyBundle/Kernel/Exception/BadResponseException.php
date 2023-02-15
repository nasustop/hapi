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
namespace ThirdPartyBundle\Kernel\Exception;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Throwable;

class BadResponseException extends BusinessException
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        if (empty($code)) {
            $code = ErrorCode::SERVER_ERROR;
        }
        parent::__construct(code: $code, message: $message, previous: $previous);
    }
}
