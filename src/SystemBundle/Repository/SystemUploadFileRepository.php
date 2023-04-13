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
namespace SystemBundle\Repository;

use App\Repository\Repository;
use SystemBundle\Model\SystemUploadFileModel;

class SystemUploadFileRepository extends Repository
{
    public const ENUM_HANDLE_STATUS_WAIT = 'wait';

    public const ENUM_HANDLE_STATUS_PROCESSING = 'processing';

    public const ENUM_HANDLE_STATUS_FINISH = 'finish';

    public const ENUM_HANDLE_STATUS = [self::ENUM_HANDLE_STATUS_WAIT => 'wait', self::ENUM_HANDLE_STATUS_PROCESSING => 'processing', self::ENUM_HANDLE_STATUS_FINISH => 'finish'];

    public const ENUM_HANDLE_STATUS_DEFAULT = self::ENUM_HANDLE_STATUS_WAIT;

    protected SystemUploadFileModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumHandleStatus(): array
    {
        return self::ENUM_HANDLE_STATUS;
    }

    public function enumHandleStatusDefault(): string
    {
        return self::ENUM_HANDLE_STATUS_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemUploadFileModel
    {
        if (empty($this->model)) {
            $this->model = container()->get(SystemUploadFileModel::class);
        }
        return $this->model;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::formatColumnData($data);
        if (! empty($data)) {
            $data['file_size_format'] = $this->format_filesize($data['file_size']);
        }
        return $data;
    }

    protected function format_filesize(int $filesize): string
    {
        $bytes = floatval($filesize);
        switch ($bytes) {
            case $bytes < 1024:
                $result = $bytes . 'B';
                break;
            case $bytes < pow(1024, 2) :
                $result = strval(round($bytes / 1024, 2)) . 'KB';
                break;
            default:
                $result = $bytes / pow(1024, 2);
                $result = strval(round($result, 2)) . 'MB';
                break;
        }
        return $result;
    }
}
