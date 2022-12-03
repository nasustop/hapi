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

use Nasustop\HapiBase\Auth\AuthManager;
use Nasustop\HapiBase\Auth\AuthManagerFactory;
use Nasustop\HapiBase\HttpServer\RequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SystemBundle\Event\SystemOperationLogEvent;
use SystemBundle\Job\SystemOperationLogJob;
use SystemBundle\Service\SystemUserService;

class SystemOperationLogMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($request->getMethod() == 'GET') {
            return $response;
        }
        $request = $this->container->get(ServerRequestInterface::class);
        $attributes = $request->getAttributes();
        $user_id = $attributes['auth']['user_id'] ?? 0;

        $request = $this->container->get(RequestInterface::class);
        $data = [
            'user_id' => $user_id,
            'from_ip' => $request->getRequestIp(),
            'request_uri' => $request->getPathInfo(),
            'request_method' => $request->getMethod(),
            'api_alias' => $request->getRequestApiAlias(),
            'api_name' => $request->getRequestApiName(),
            'params' => $request->all(),
        ];
        $data = $this->fillLoginApiUserID($response, $data);
        event(new SystemOperationLogEvent($data));
        return $response;
    }

    public function fillLoginApiUserID(ResponseInterface $response, array $data): array
    {
        if ($data['api_alias'] != 'app.system.login') {
            return $data;
        }
        $responseData = @json_decode($response->getBody()->getContents(), true);
        if (empty($responseData['data']['token'])) {
            return $data;
        }
        $authManager = $this->container->get(AuthManagerFactory::class);
        $user = $authManager->guard('backend')->validateToken($responseData['data']['token']);
        if (empty($user['user_id'])) {
            return $data;
        }
        $data['user_id'] = $user['user_id'];
        return $data;
    }
}
