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
namespace SystemBundle\Basic;

use App\Exception\RuntimeErrorException;
use League\Flysystem\Filesystem;
use SystemBundle\Contract\ExportFileInterface;
use SystemBundle\Traits\ExportTypeServiceTrait;

abstract class BasicExportFileService implements ExportFileInterface
{
    use ExportTypeServiceTrait;

    protected Filesystem $filesystem;

    /**
     * get Filesystem.
     */
    public function getFilesystem(): Filesystem
    {
        if (empty($this->filesystem)) {
            $this->filesystem = make(Filesystem::class);
        }
        return $this->filesystem;
    }

    public function getExportFilePath(): string
    {
        return '/export/' . $this->getExportType(get_class($this)) . '/' . date('Y-m-d') . '/';
    }

    public function exportCsv(array $data): string
    {
        $path = $this->getExportFilePath();
        $titleKey = [];
        $titleValue = [];
        foreach ($this->getExportTitle() as $key => $value) {
            $titleKey[] = $key;
            $titleValue[] = $value;
        }

        $fileDir = storage_path('csv') . $path;
        if (! is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
        $path .= $this->getExportFileName();
        $fn = $fileDir . $this->getExportFileName();
        $fh = fopen($fn, 'w');
        fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fh, $titleValue);
        foreach ($data as $value) {
            $row = [];
            foreach ($titleKey as $key) {
                $row[] = $value[$key] ?? '';
            }
            fputcsv($fh, $row);
        }
        fclose($fh);

        if ($this->getFilesystem()->fileExists($path)) {
            throw new RuntimeErrorException('文件已存在，请更换文件名');
        }
        $this->getFilesystem()->write($path, file_get_contents($fn));
        $driver = config('file.default');
        $base_uri = config(sprintf('file.storage.%s.domain', $driver));
        return $base_uri . $path;
    }
}
