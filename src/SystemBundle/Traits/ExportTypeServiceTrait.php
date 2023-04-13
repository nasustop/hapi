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
namespace SystemBundle\Traits;

use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use SystemBundle\Contract\ExportFileInterface;
use SystemBundle\Service\ExportFile\DemoExportFileService;

trait ExportTypeServiceTrait
{
    protected array $serviceData = [
        [
            'name' => '测试导出',
            'type' => 'demo',
            'class' => DemoExportFileService::class,
        ],
    ];

    public function getExportTypeList(): array
    {
        return $this->serviceData;
    }

    public function getExportService(string $export_type): ExportFileInterface
    {
        $serviceData = array_column($this->serviceData, null, 'type');
        $className = $serviceData[$export_type]['class'] ?? null;
        if (is_null($className)) {
            throw new BadRequestHttpException('未知的导出类型');
        }
        return new $className();
    }

    public function getExportType(string $className): string
    {
        $serviceData = array_column($this->serviceData, null, 'class');
        $type = $serviceData[$className]['type'] ?? null;
        if (is_null($type)) {
            throw new BadRequestHttpException('未知的导出类型');
        }
        return $type;
    }
}
