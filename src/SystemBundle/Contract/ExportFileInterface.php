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
namespace SystemBundle\Contract;

interface ExportFileInterface
{
    public function handle(array $filter): string;

    public function getExportTitle(): array;

    public function getExportFileName(): string;

    public function getExportFilePath(): string;

    public function exportCsv(array $data);
}
