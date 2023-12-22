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

namespace ThirdPartyBundle\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use ThirdPartyBundle\Event\HttpRequestEvent;

#[Listener]
class HttpRequestListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            HttpRequestEvent::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof HttpRequestEvent) {
            if (config('app_env', 'dev') === 'prd') {
                pushQueue($event);
            } else {
                $event->run();
            }
        }
    }
}
