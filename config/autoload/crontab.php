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
    'enable' => false,
    'crontab' => [
        (new \Hyperf\Crontab\Crontab())
            ->setName('DemoCrontab')
            ->setRule('* * * * *')
            ->setCallback([App\Crontab\DemoCrontab::class, 'execute'])
            ->setMemo('这是一个示例的定时任务'),
    ],
];
