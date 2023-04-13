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
    protected string $queue = 'default';

    public function __construct(protected array $logger)
    {
    }

    public function handle(): string
    {
        $service = $this->getContainer()->get(SystemOperationLogService::class);
        $service->saveData($this->logger);
        return self::ACK;
    }
}
