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

namespace SystemBundle\Event;

class SystemOperationLogEvent
{
    public function __construct(protected array $log) {}

    /**
     * get Log.
     */
    public function getLog(): array
    {
        return $this->log;
    }
}
