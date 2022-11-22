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
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteAliasMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $attributes = $request->getAttributes();
        $attributes = @json_decode(json_encode($attributes), true);
        $alias = $attributes ? ($attributes['Hyperf\HttpServer\Router\Dispatched']['handler']['options']['alias'] ?? '') : '';
        $name = $attributes ? ($attributes['Hyperf\HttpServer\Router\Dispatched']['handler']['options']['name'] ?? '') : '';
        // 获取路由文件中设置的别名和名称
        $request->withAttribute('route.alias', $alias);
        $request->withAttribute('route.name', $name);
        return $handler->handle($request);
    }
}
