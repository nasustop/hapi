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

namespace SystemBundle\Middleware;

use App\Exception\TokenErrorException;
use App\Exception\TokenExpiredException;
use Firebase\JWT\ExpiredException;
use Nasustop\HapiAuth\AuthManagerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BackendTokenMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $authManage = $this->container->get(id: AuthManagerFactory::class);
            $user = $authManage->guard(guard: 'backend')->user();
        } catch (\Throwable $exception) {
            if ($exception instanceof ExpiredException) {
                throw new TokenExpiredException(message: $exception->getMessage(), previous: $exception);
            }
            throw new TokenErrorException(message: $exception->getMessage(), previous: $exception);
        }
        $request = $request->withAttribute('auth', $user);

        return $handler->handle(request: $request);
    }
}
