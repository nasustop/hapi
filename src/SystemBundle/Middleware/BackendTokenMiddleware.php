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

use Firebase\JWT\ExpiredException;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Nasustop\HapiBase\Auth\AuthManagerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class BackendTokenMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $authManage = $this->container->get(AuthManagerFactory::class);
            $user = $authManage->guard('backend')->user();
            $request = $request->withAttribute('auth', $user);
        } catch (Throwable $exception) {
            if ($exception instanceof ExpiredException) {
                throw new UnauthorizedHttpException($exception->getMessage());
            }
            throw $exception;
        }

        return $handler->handle($request);
    }
}
