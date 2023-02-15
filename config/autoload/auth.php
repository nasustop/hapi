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
    'backend' => [
        'provider' => \SystemBundle\Auth\AuthUserProvider::class,
        'cache' => 'memcached',
        'support_admin_user' => env('BACKEND_SUPPORT_ADMIN_USER', ''),
        'jwt' => [
            'alg' => env('JWT_ALG', 'HS256'),
            'secret' => env('JWT_SECRET', 'hapi'),
            'iss' => env('JWT_ISS', 'hapi'),
            'aud' => env('JWT_AUD', 'hapi'),
            'exp' => env('JWT_EXPIRED', 7200),
            'leeway' => env('JWT_LEEWAY', 300),
            'header' => env('JWT_HEADER', 'authorization'),
            'prefix' => env('JWT_PREFIX', 'bear'),
        ],
    ],
];
