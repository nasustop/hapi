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

class SystemUploadCsvHandleBatchJob extends Job
{
    public function __construct(protected int $upload_id, protected array $lineNumData, protected bool $useQueue) {}

    public function handle(): string
    {
        // TODO: Implement handle() method.
        $service = make(SystemUploadFileService::class);
        $service->handleUploadFileBatchData($this->upload_id, $this->lineNumData, $this->useQueue);
        return self::ACK;
    }
}
