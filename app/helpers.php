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
            return make(\Hyperf\Redis\RedisFactory::class, [container()->get(\Hyperf\Contract\ConfigInterface::class)])->get($pool);
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
            return make(\Hyperf\Cache\CacheManager::class, [
                container()->get(\Hyperf\Contract\ConfigInterface::class),
                container()->get(\Hyperf\Contract\StdoutLoggerInterface::class),
            ])->getDriver($driver);
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
            return make(\Hyperf\Logger\LoggerFactory::class, [
                container(),
                container()->get(\Hyperf\Contract\ConfigInterface::class),
            ])->get($name, $group);
        }
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
