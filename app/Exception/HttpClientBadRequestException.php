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
namespace App\Exception;

use App\Constants\ErrorCode;

class HttpClientBadRequestException extends BusinessException
{
    protected $code = ErrorCode::SERVER_ERROR;

    public function __construct(string $message = '', \Throwable $previous = null)
    {
        if (empty($message)) {
            $message = ErrorCode::getMessage($this->code);
        }
        parent::__construct(code: $this->code, message: $message, previous: $previous);
    }
}
