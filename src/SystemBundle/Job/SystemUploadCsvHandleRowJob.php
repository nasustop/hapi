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
use SystemBundle\Service\SystemUploadFileService;

class SystemUploadCsvHandleRowJob extends Job
{
    protected string $queue = 'default';

    public function __construct(protected int $upload_id, protected int $line_num) {}

    public function handle(): string
    {
        $service = $this->getContainer()->get(SystemUploadFileService::class);
        $service->handleUploadFileRowData($this->upload_id, $this->line_num);
        return self::ACK;
    }
}
