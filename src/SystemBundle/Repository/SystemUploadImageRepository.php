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
use Hyperf\HttpMessage\Exception\ServerErrorHttpException;
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
        if (empty($this->model)) {
            $this->model = make(SystemUploadImageModel::class);
        }
        return $this->model;
    }

    public function getInfo($filter, $column = '*', $orderBy = []): array
    {
        $info = parent::getInfo($filter, $column, $orderBy);
        if (empty($info)) {
            return $info;
        }
        $base_uri = config(sprintf('file.storage.%s.domain', $info['img_storage']));
        if (empty($base_uri)) {
            throw new ServerErrorHttpException(sprintf('[%s]存储容器配置错误，未配置访问domain!', $info['img_storage']));
        }
        $info['bash_uri'] = $base_uri;
        $info['full_url'] = $base_uri . $info['img_url'];
        return $info;
    }

    public function getLists($filter = [], $column = '*', $page = 0, $page_size = 0, $orderBy = []): array
    {
        $result = parent::getLists($filter, $column, $page, $page_size, $orderBy);
        foreach ($result as $key => $value) {
            $base_uri = config(sprintf('file.storage.%s.domain', $value['img_storage']));
            if (empty($base_uri)) {
                throw new ServerErrorHttpException(sprintf('[%s]存储容器配置错误，未配置访问domain!', $value['img_storage']));
            }
            $result[$key]['base_uri'] = $base_uri;
            $result[$key]['full_url'] = $base_uri . $value['img_url'];
        }
        return $result;
    }
}
