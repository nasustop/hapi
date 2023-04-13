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
    Hyperf\Crontab\Process\CrontabDispatcherProcess::class,
    \Nasustop\HapiSidecar\Example\demo\php\DemoProcess::class,
    \Nasustop\HapiSidecar\Example\mongo\php\MongoProcess::class,
];
