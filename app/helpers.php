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
use Hyperf\Snowflake\IdGeneratorInterface;

if (! function_exists('container')) {
    /**
     * 获取容器对象.
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

if (! function_exists('apiResponseHttpStatus')) {
    function apiResponseHttpStatus(Throwable $throwable): int
    {
        $code = $throwable->getCode();
        return $code ?: \App\Constants\ErrorCode::SERVER_ERROR;
    }
}

if (! function_exists('apiResponseMsgCode')) {
    function apiResponseMsgCode(Throwable $throwable): int
    {
        $code = $throwable->getCode();
        if (empty($code)) {
            if ($throwable instanceof \Hyperf\HttpMessage\Exception\HttpException) {
                $code = $throwable->getStatusCode();
            }
        }
        $code = (int) $code;
        if (empty($code)) {
            $code = \App\Constants\ErrorCode::SERVER_ERROR;
        }
        return $code;
    }
}

if (! function_exists('generaSnowID')) {
    function generateSnowID(): int
    {
        $generator = \Hyperf\Utils\ApplicationContext::getContainer()->get(id: IdGeneratorInterface::class);
        return $generator->generate();
    }
}

if (! function_exists('generateUUID')) {
    function generateUUID(): string
    {
        return '';
    }
}
