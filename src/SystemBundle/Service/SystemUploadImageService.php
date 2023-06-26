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
namespace SystemBundle\Service;

use Hyperf\Filesystem\FilesystemFactory;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Exception\ServerErrorHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use SystemBundle\Repository\SystemUploadImageRepository;

/**
 * @method getInfo(array $filter, array|string $columns = '*', array $orderBy = [])
 * @method getLists(array $filter = [], array|string $columns = '*', int $page = 0, int $pageSize = 0, array $orderBy = [])
 * @method count(array $filter)
 * @method pageLists(array $filter = [], array|string $columns = '*', int $page = 1, int $pageSize = 100, array $orderBy = [])
 * @method insert(array $data)
 * @method batchInsert(array $data)
 * @method saveData(array $data)
 * @method updateBy(array $filter, array $data)
 * @method updateOneBy(array $filter, array $data)
 * @method deleteBy(array $filter)
 * @method deleteOneBy(array $filter)
 */
class SystemUploadImageService
{
    protected const basePath = 'uploads/images/';

    protected SystemUploadImageRepository $repository;

    protected FilesystemFactory $filesystem;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): SystemUploadImageRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(SystemUploadImageRepository::class);
        }
        return $this->repository;
    }

    /**
     * get Filesystem.
     */
    public function getFilesystem(): FilesystemFactory
    {
        if (empty($this->filesystem)) {
            $this->filesystem = make(FilesystemFactory::class);
        }
        return $this->filesystem;
    }

    public function uploadImg(UploadedFile $file, string $storage, string $img_brief): array
    {
        if (empty(config(sprintf('file.storage.%s', $storage)))) {
            throw new ServerErrorHttpException(sprintf('[%s]存储容器配置不存在', $storage));
        }
        if ($file->getSize() <= 0) {
            throw new BadRequestHttpException('图片上传失败');
        }

        $img_size = $file->getSize();
        $max_upload_img_size = config('file.upload_max_size', 5);
        if ($img_size > $max_upload_img_size * 1024 * 1024) {
            throw new BadRequestHttpException('上传图片大小不能超过' . $max_upload_img_size . 'M');
        }

        $contents = file_get_contents($file->getRealPath());
        $basePath = self::basePath . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        $filePath = $basePath . md5($contents) . '.' . $file->getExtension();
        $this->getFilesystem()->get($storage)->write($filePath, $contents);

        $params = [
            'img_storage' => $storage,
            'img_name' => $file->getClientFilename(),
            'img_type' => $file->getClientMediaType(),
            'img_url' => $filePath,
            'img_size' => $img_size,
            'img_brief' => $img_brief,
        ];
        $id = $this->getRepository()->insertGetId($params);

        return $this->getRepository()->getInfo(['img_id' => $id]);
    }

    public function deleteImage($filter): bool
    {
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('删除的图片不存在');
        }
        $this->getFilesystem()->get($info['img_storage'])->delete($info['img_url']);
        return $this->getRepository()->deleteOneBy($filter);
    }
}
