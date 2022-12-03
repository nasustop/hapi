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
if (! function_exists('container')) {
    /**
     * 获取container.
     */
    function container(): Psr\Container\ContainerInterface
    {
        return \Hyperf\Utils\ApplicationContext::getContainer();
    }
}

if (! function_exists('redis')) {
    /**
     * 获取redis连接.
     */
    function redis(string $pool = 'default'): Hyperf\Redis\RedisProxy
    {
        try {
            return container()->get(Hyperf\Redis\RedisFactory::class)->get($pool);
        } catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
            return make(\Hyperf\Redis\RedisFactory::class)->get($pool);
        }
    }
}

if (! function_exists('memcached')) {
    /**
     * 获取memcached连接.
     */
    function memcached(string $pool = 'default'): Nasustop\HapiBase\Memcached\MemcachedProxy
    {
        try {
            return container()->get(\Nasustop\HapiBase\Memcached\MemcachedFactory::class)->get($pool);
        } catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
            return make(\Nasustop\HapiBase\Memcached\MemcachedFactory::class)->get($pool);
        }
    }
}

if (! function_exists('cache')) {
    /**
     * 获取cache.
     */
    function cache(string $driver = 'default'): Psr\SimpleCache\CacheInterface
    {
        try {
            return container()->get(Hyperf\Cache\CacheManager::class)->getDriver($driver);
        } catch (Psr\Container\NotFoundExceptionInterface|Psr\Container\ContainerExceptionInterface $e) {
            return make(\Hyperf\Cache\CacheManager::class)->getDriver($driver);
        }
    }
}

if (! function_exists('logger')) {
    /**
     * 获取日志.
     */
    function logger(string $name = 'hyperf', string $group = 'default'): Psr\Log\LoggerInterface
    {
        try {
            return container()->get(\Hyperf\Logger\LoggerFactory::class)->get($name, $group);
        } catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
            return make(\Hyperf\Logger\LoggerFactory::class)->get($name, $group);
        }
    }
}

if (! function_exists('pushQueue')) {
    /**
     * 触发队列任务.
     */
    function pushQueue(Nasustop\HapiBase\Queue\Job\JobInterface $job)
    {
        (new \Nasustop\HapiBase\Queue\Producer($job))->onQueue($job->getQueue())->dispatcher();
    }
}

if (! function_exists('event')) {
    /**
     * 触发event事件.
     */
    function event(object $event)
    {
        try {
            $eventDispatcher = container()->get(\Hyperf\Event\EventDispatcher::class);
        } catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
            $eventDispatcher = make(\Hyperf\Event\EventDispatcher::class);
        }
        $eventDispatcher->dispatch($event);
    }
}

if (! function_exists('storage_path')) {
    /**
     * 获取storage_path.
     */
    function storage_path(string $path = ''): string
    {
        $storage_path = BASE_PATH . '/' . env('STORAGE_PATH', 'storage') . ($path ? '/' . $path : $path);
        if (! is_dir($storage_path)) {
            mkdir($storage_path, 0777, true);
        }
        return $storage_path;
    }
}

if (! function_exists('loadDirFiles')) {
    function loadDirFiles($path = BASE_PATH . '/routes')
    {
        $files = scandir($path);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $file_path = $path . '/' . $file;
            if (is_file($file_path)) {
                require_once $file_path;
            }
        }
    }
}
