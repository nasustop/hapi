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

use Hyperf\HttpMessage\Upload\UploadedFile;

interface UploadFileInterface
{
    public function is_batch_handle(): bool;

    public function getHeaderTitle(): array;

    public function checkFile(UploadedFile $file);

    public function checkFileRelID(int $file_rel_id);

    public function putRawFileDiskPath(string $fileType, UploadedFile $file): string;

    public function handle(int $file_rel_id, array $rawData);

    public function checkHandleData(array $data): bool;
}
