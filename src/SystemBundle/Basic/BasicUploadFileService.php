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
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\Filesystem;
use SystemBundle\Contract\UploadFileInterface;

abstract class BasicUploadFileService implements UploadFileInterface
{
    protected bool $is_batch_handle = false;

    public function is_batch_handle(): bool
    {
        return $this->is_batch_handle;
    }

    public function checkFile(UploadedFile $file)
    {
        $this->checkFileSize($file);
        $this->checkFileType($file);
    }

    public function checkFileRelID(int $file_rel_id)
    {
        // TODO: Implement checkFileRelID() method.
    }

    public function putRawFileDiskPath(string $fileType, UploadedFile $file): string
    {
        $fileName = $file->getClientFilename();
        $data = explode('.', $fileName);
        $name = array_shift($data);
        $path = $fileType . '/' . date('Y-m-d_H:i:s') . '/' . md5($name) . '.' . implode('.', $data);
        $filesystem = container()->get(Filesystem::class);
        $filesystem->write($path, $file->getStream()->getContents());
        $driver = config('file.default');
        $base_uri = config(sprintf('file.storage.%s.domain', $driver));
        return $base_uri . $path;
    }

    public function checkHandleData(array $data): bool
    {
        if ($this->is_batch_handle()) {
            foreach ($data as $row) {
                $this->checkHandleRow($row);
            }
        } else {
            $this->checkHandleRow($data);
        }
        return true;
    }

    protected function checkFileSize(UploadedFile $file)
    {
        if ($file->getSize() > 50 * 1024 * 1024) {
            throw new BadRequestHttpException('上传文件大小不能超过50M');
        }
    }

    protected function checkFileType(UploadedFile $file)
    {
        if ($file->getExtension() !== 'csv') {
            throw new BadRequestHttpException('上传文件类型必须是csv格式');
        }
    }

    protected function checkHandleRow(array $row)
    {
        foreach ($this->getHeaderTitle() as $key => $value) {
            if ($value['is_need'] && empty($row[$key])) {
                throw new RuntimeErrorException($value['remarks']);
            }
        }
    }
}
