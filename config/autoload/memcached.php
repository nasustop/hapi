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
        'servers' => env('MEMCACHED_SERVERS', env('MEMCACHED_HOST', '127.0.0.1:11211:100')),
        'username' => env('MEMCACHED_USERNAME', 'hapi'),
        'password' => env('MEMCACHED_PASSWORD', '123456'),
        'options' => [],
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 1,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('MEMCACHED_MAX_IDLE_TIME', 60),
        ],
    ],
];
