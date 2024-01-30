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

use App\Exception\RuntimeErrorException;
use App\Exception\RuntimeWarningException;
use Nasustop\HapiQueue\Job\Job;
use SystemBundle\Repository\SystemExportFileRepository;
use SystemBundle\Service\SystemExportFileService;
use SystemBundle\Traits\ExportTypeServiceTrait;

class SystemExportFileJob extends Job
{
    use ExportTypeServiceTrait;

    protected string $queue = 'default';

    public function __construct(protected int $export_id) {}

    public function handle(): string
    {
        $service = make(SystemExportFileService::class);
        $info = $service->getRepository()->getInfoByID($this->export_id);
        if (empty($info)) {
            throw new RuntimeErrorException('导出记录不存在，无法执行导出任务');
        }
        if ($info['handle_status'] !== SystemExportFileRepository::ENUM_HANDLE_STATUS_WAIT) {
            throw new RuntimeWarningException('导出状态不是待处理，请勿重复执行导出任务');
        }
        $service->getRepository()->updateOneBy(['id' => $this->export_id], [
            'handle_status' => SystemExportFileRepository::ENUM_HANDLE_STATUS_FINISH,
            'finish_time' => date('Y-m-d H:i:s'),
        ]);
        try {
            $exportService = $this->getExportService($info['export_type']);
            $path = $exportService->handle($info['request_data']);
            $service->getRepository()->updateOneBy(['id' => $this->export_id], [
                'handle_status' => SystemExportFileRepository::ENUM_HANDLE_STATUS_FINISH,
                'finish_time' => date('Y-m-d H:i:s'),
                'file_url' => $path,
                'file_name' => $exportService->getExportFileName(),
            ]);
        } catch (\Exception $exception) {
            $service->getRepository()->updateOneBy(['id' => $this->export_id], [
                'handle_status' => SystemExportFileRepository::ENUM_HANDLE_STATUS_FAIL,
                'finish_time' => date('Y-m-d H:i:s'),
                'error_message' => $exception->getMessage(),
            ]);
            throw $exception;
        }

        return self::ACK;
    }
}
