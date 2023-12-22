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

namespace SystemBundle\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Hyperf\HttpServer\Router\RouteCollector;
use Psr\Container\ContainerInterface;
use SystemBundle\Repository\SystemPowerRepository;
use SystemBundle\Service\SystemApiService;

#[Command]
class SystemApiCommand extends HyperfCommand
{
    public function __construct(private ContainerInterface $container)
    {
        parent::__construct('hapi:system:api');
    }

    public function handle()
    {
        $factory = $this->container->get(DispatcherFactory::class);
        $router = $factory->getRouter('http');
        $data = $this->analyzeRouter($router);
        $service = make(SystemApiService::class);
        $oldApiData = $service->getLists();
        $oldApiData = array_column($oldApiData, null, 'api_alias');
        $insertData = [];
        foreach ($data as $alias => $value) {
            if (! empty($oldApiData[$alias])) {
                $service->updateOneBy(['api_alias' => $alias], [
                    'api_name' => $value['name'],
                    'api_method' => $value['method'],
                    'api_uri' => $value['uri'],
                    'api_action' => $value['action'],
                ]);
                $oldApiData[$alias] = null;
            } else {
                $insertData[] = [
                    'api_alias' => $value['alias'],
                    'api_name' => $value['name'],
                    'api_method' => $value['method'],
                    'api_uri' => $value['uri'],
                    'api_action' => $value['action'],
                ];
            }
        }
        if (! empty($insertData)) {
            $service->batchInsert($insertData);
        }
        $deleteIds = array_column(array_filter($oldApiData), 'api_id');
        if (! empty($deleteIds)) {
            $service->deleteBy(['api_id' => $deleteIds]);
            $powerRepository = make(SystemPowerRepository::class);
            $powerRepository->deleteBy(['children_id' => $deleteIds, 'children_type' => SystemPowerRepository::ENUM_CHILDREN_TYPE_API]);
        }
        $this->info('success');
    }

    protected function analyzeRouter(RouteCollector $router)
    {
        $data = [];
        [$staticRouters, $variableRouters] = $router->getData();
        foreach ($staticRouters as $method => $items) {
            foreach ($items as $handler) {
                $this->analyzeHandler($data, $method, $handler);
            }
        }
        foreach ($variableRouters as $method => $items) {
            foreach ($items as $item) {
                if (is_array($item['routeMap'] ?? false)) {
                    foreach ($item['routeMap'] as $routeMap) {
                        $this->analyzeHandler($data, $method, $routeMap[0]);
                    }
                }
            }
        }
        return $data;
    }

    protected function analyzeHandler(array &$data, string $method, Handler $handler)
    {
        $uri = $handler->route;
        if (is_array($handler->callback)) {
            $action = $handler->callback[0] . '::' . $handler->callback[1];
        } elseif (is_string($handler->callback)) {
            $action = $handler->callback;
        } else {
            $action = 'Closure';
        }
        $alias = $handler->options['alias'] ?? '';
        $name = $handler->options['name'] ?? '';
        if (empty($alias)) {
            throw new \Exception("{$uri}|{$action}接口的alias不能为空");
        }
        if (isset($data[$alias])) {
            if ($data[$alias]['uri'] !== $uri) {
                throw new \Exception("{$uri}|{$action}接口的alias[{$alias}]不能和{$data[$alias]['uri']}|{$data[$alias]['action']}接口的alias重复");
            }
            $data[$alias]['method'][] = $method;
            return;
        }
        $data[$alias] = [
            'alias' => $alias,
            'name' => $name,
            'method' => [$method],
            'uri' => $uri,
            'action' => $action,
        ];
    }
}
