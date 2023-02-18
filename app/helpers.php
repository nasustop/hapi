<?php

if (! function_exists('container')) {
    /**
     * 获取容器对象.
     */
    function container(): Psr\Container\ContainerInterface
    {
        return \Hyperf\Utils\ApplicationContext::getContainer();
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
    function memcached(string $pool = 'default'): Nasustop\HapiMemcached\MemcachedProxy
    {
        try {
            return container()->get(\Nasustop\HapiMemcached\MemcachedFactory::class)->get($pool);
        } catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {
            return make(\Nasustop\HapiMemcached\MemcachedFactory::class)->get($pool);
        }
    }
}