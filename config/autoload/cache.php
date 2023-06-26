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
return [
    'default' => [
        'driver' => \Nasustop\HapiCache\RedisDriver::class,
        'packer' => Hyperf\Utils\Packer\PhpSerializerPacker::class,
        'prefix' => 'c:',
        'pool' => 'default',
    ],
    'memcached' => [
        'driver' => \Nasustop\HapiCache\MemcachedDriver::class,
        'packer' => Hyperf\Utils\Packer\PhpSerializerPacker::class,
        'prefix' => 'c:',
        'pool' => 'default',
    ],
    'memory' => [
        'driver' => \Nasustop\HapiCache\MemoryDriver::class,
        'packer' => Hyperf\Utils\Packer\PhpSerializerPacker::class,
        'size' => 10240, // 最大缓存行数
        'memory_size' => 1024 * 1024 * 1024 * 2, // 最大占用内存
        'row_size' => 4096, // 每个缓存的最大长度
        'ttl' => 3600 * 24 * 365, // 默认缓存时间
        'clean_size' => 500, // 超出最大缓存行数或最大占用内存时，删除旧数据的数量，小于等于0时全部删除
    ],
];
