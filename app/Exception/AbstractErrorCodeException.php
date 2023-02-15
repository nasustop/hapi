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
use Hyperf\HttpMessage\Exception\HttpException;
use Swoole\Http\Status;
use Throwable;

abstract class AbstractErrorCodeException extends HttpException
{
    protected $code = ErrorCode::SERVER_ERROR;

    public function __construct($message = '', Throwable $previous = null)
    {
        if (empty($message)) {
            $message = ErrorCode::getMessage($this->code);
        }
        parent::__construct(statusCode: Status::OK, message: $message, code: $this->code, previous: $previous);
    }
}
