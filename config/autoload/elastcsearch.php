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
    'hosts' => [
        'http://' . env('ELASTICSEARCH_HOST', 'hapi-develop-elasticsearch') . ':' . env('ELASTICSEARCH_PORT', 9200),
    ],
];
