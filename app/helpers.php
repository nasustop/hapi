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
use App\Constants\ErrorCode;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Hyperf\Event\EventDispatcher;
use Hyperf\Guzzle\PoolHandler;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Swoole\Coroutine;

if (! function_exists('redis')) {
    /**
     * 获取redis连接.
     */
    function redis(string $pool = 'default'): Hyperf\Redis\RedisProxy
    {
        /** @var RedisFactory $redisFactory */
        $redisFactory = make(RedisFactory::class);
        return $redisFactory->get($pool);
    }
}

if (! function_exists('event')) {
    /**
     * 触发event事件.
     */
    function event(object $event)
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = make(EventDispatcher::class);
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
        return $code ?: ErrorCode::SERVER_ERROR;
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
        if (empty($code) || ! is_int($code)) {
            $code = ErrorCode::SERVER_ERROR;
        }
        return $code;
    }
}

if (! function_exists('logger')) {
    function logger(string $name = 'default', string $group = ''): Psr\Log\LoggerInterface
    {
        /** @var LoggerFactory $loggerFactory */
        $loggerFactory = make(LoggerFactory::class);
        return $loggerFactory->get($name, $group);
    }
}

if (! function_exists('get_rand_string')) {
    function get_rand_string($length): string
    {
        // 字符组合
        $str = '#abcdefghilkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randStr = '';
        for ($i = 0; $i < $length; ++$i) {
            $num = mt_rand(0, $len);
            $randStr .= $str[$num];
        }
        return $randStr;
    }
}

if (! function_exists('elasticsearch_client')) {
    function elasticsearch_client(): Client
    {
        $builder = ClientBuilder::create();
        if (Coroutine::getCid() > 0) {
            $handler = make(PoolHandler::class, [
                'option' => [
                    'max_connections' => 50,
                ],
            ]);
            $builder->setHandler($handler);
        }

        return $builder->setHosts(['http://' . env('ELASTICSEARCH_HOST', 'hapi-develop-elasticsearch') . ':9200'])->build();
    }
}
