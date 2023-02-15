<?php

namespace App\Exception\Token;

use App\Constants\ErrorCode;
use App\Exception\AbstractErrorCodeException;

class TokenErrorException extends AbstractErrorCodeException
{
    protected $code = ErrorCode::TOKEN_ERROR;

}