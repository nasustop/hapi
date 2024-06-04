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

namespace App\Process\Queue;

use Hyperf\Process\Annotation\Process;
use Nasustop\HapiQueue\Consumer;

#[Process]
class QueueDefaultProcess extends Consumer
{
    protected string $queue = 'default';
}
