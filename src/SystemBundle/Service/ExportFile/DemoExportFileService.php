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
namespace SystemBundle\Service\ExportFile;

use SystemBundle\Basic\BasicExportFileService;
use SystemBundle\Service\SystemWechatService;

class DemoExportFileService extends BasicExportFileService
{
    protected SystemWechatService $service;

    public function getExportFileName(): string
    {
        if (empty($this->fileName)) {
            mt_srand();
            $this->fileName = date('YmdHis') . mt_rand(0, 9999) . '-demo-export.csv';
        }
        return $this->fileName;
    }

    public function getExportTitle(): array
    {
        return [
            'id' => 'ID',
            'driver' => '类型',
            'app_id' => 'AppID',
            'secret' => 'AppSecret',
            'token' => 'Token',
        ];
    }

    public function handle(array $filter): string
    {
        $data = $this->getService()->getRepository()->getLists($filter);
        return $this->exportCsv($data);
    }

    public function getService(): SystemWechatService
    {
        if (empty($this->service)) {
            $this->service = make(SystemWechatService::class);
        }
        return $this->service;
    }
}
