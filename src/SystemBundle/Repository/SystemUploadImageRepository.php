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
use Hyperf\Di\Annotation\Inject;
use SystemBundle\Model\SystemUploadImageModel;

class SystemUploadImageRepository extends Repository
{
    public const ENUM_IMG_STORAGE_LOCAL = 'local';

    public const ENUM_IMG_STORAGE_FTP = 'ftp';

    public const ENUM_IMG_STORAGE_MEMORY = 'memory';

    public const ENUM_IMG_STORAGE_S3 = 's3';

    public const ENUM_IMG_STORAGE_MINIO = 'minio';

    public const ENUM_IMG_STORAGE_OSS = 'oss';

    public const ENUM_IMG_STORAGE_QINIU = 'qiniu';

    public const ENUM_IMG_STORAGE_COS = 'cos';

    public const ENUM_IMG_STORAGE = [self::ENUM_IMG_STORAGE_LOCAL => 'local', self::ENUM_IMG_STORAGE_FTP => 'ftp', self::ENUM_IMG_STORAGE_MEMORY => 'memory', self::ENUM_IMG_STORAGE_S3 => 's3', self::ENUM_IMG_STORAGE_MINIO => 'minio', self::ENUM_IMG_STORAGE_OSS => 'oss', self::ENUM_IMG_STORAGE_QINIU => 'qiniu', self::ENUM_IMG_STORAGE_COS => 'cos'];

    public const ENUM_IMG_STORAGE_DEFAULT = self::ENUM_IMG_STORAGE_LOCAL;

    #[Inject]
    protected SystemUploadImageModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumImgStorage(): array
    {
        return self::ENUM_IMG_STORAGE;
    }

    public function enumImgStorageDefault(): string
    {
        return self::ENUM_IMG_STORAGE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemUploadImageModel
    {
        return $this->model;
    }
}
