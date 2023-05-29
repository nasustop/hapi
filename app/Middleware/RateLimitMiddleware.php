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
namespace App\Middleware;

use Hyperf\Context\ApplicationContext;
use Nasustop\HapiRateLimit\RedisTokenBucket;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimitMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $rate = new RedisTokenBucket(redis(), 'hapi-rate-limit', 10, 5, 1);

        if (! $rate->getToken(1)) {
            $response = ApplicationContext::getContainer()->get(\Nasustop\HapiBase\HttpServer\ResponseInterface::class);
            return $response->error(data: [], code: 429, msg: 'To Many Requests.');
        }
        return $handler->handle($request);
    }
}
