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
namespace SystemBundle\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use SystemBundle\Event\SystemOperationLogEvent;
use SystemBundle\Job\SystemOperationLogJob;

#[Listener]
class SystemOperationApiLogListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            SystemOperationLogEvent::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof SystemOperationLogEvent) {
            $job = new SystemOperationLogJob($event->getLog());
            if (config('app_env') === 'prd') {
                pushQueue($job);
            } else {
                $job->handle();
            }
        }
    }
}
