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
use SystemBundle\Model\SystemUploadFileMessageModel;

class SystemUploadFileMessageRepository extends Repository
{
    public const ENUM_HANDLE_STATUS_WAIT = 'wait';

    public const ENUM_HANDLE_STATUS_SUCCESS = 'success';

    public const ENUM_HANDLE_STATUS_ERROR = 'error';

    public const ENUM_HANDLE_STATUS = [self::ENUM_HANDLE_STATUS_WAIT => 'wait', self::ENUM_HANDLE_STATUS_SUCCESS => 'success', self::ENUM_HANDLE_STATUS_ERROR => 'error'];

    public const ENUM_HANDLE_STATUS_DEFAULT = self::ENUM_HANDLE_STATUS_WAIT;

    protected SystemUploadFileMessageModel $model;

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
    public function getModel(): SystemUploadFileMessageModel
    {
        if (empty($this->model)) {
            $this->model = container()->get(SystemUploadFileMessageModel::class);
        }
        return $this->model;
    }

    public function setColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'raw_data') {
                $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }
        return $data;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::formatColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'raw_data') {
                $data[$key] = ! empty($value) ? @json_decode($value, true) : $value;
            }
        }
        return $data;
    }
}
