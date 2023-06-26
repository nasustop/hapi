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
namespace SystemBundle\Job;

use Nasustop\HapiQueue\Job\Job;
use SystemBundle\Service\SystemOperationLogService;

class SystemOperationLogJob extends Job
{
    protected SystemOperationLogService $service;

    protected string $queue = 'default';

    public function __construct(protected array $logger)
    {
    }

    public function handle(): string
    {
        $this->getService()->saveData($this->logger);
        return self::ACK;
    }

    protected function getService(): SystemOperationLogService
    {
        if (empty($this->service)) {
            $this->service = make(SystemOperationLogService::class);
        }
        return $this->service;
    }
}
