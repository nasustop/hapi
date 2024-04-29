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

class SystemUploadCsvReadJob extends Job
{
    protected string $queue = 'default';

    public function __construct(protected int $upload_file_id, protected int $chunk_num, protected bool $useQueue) {}

    public function handle(): string
    {
        /** @var SystemUploadFileService $service */
        $service = \Hyperf\Support\make(SystemUploadFileService::class);
        $service->handleUploadFile($this->upload_file_id, $this->chunk_num, $this->useQueue);
        return self::ACK;
    }
}
