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
namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\HttpMessage\Exception\HttpException;
use Throwable;

class BusinessException extends HttpException
{
    public function __construct(int $code = 0, string $message = null, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = ErrorCode::getMessage($code);
        }

        parent::__construct(ErrorCode::SUCCESS, $message, $code, $previous);
    }
}
