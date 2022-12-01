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
use Hyperf\HttpMessage\Exception\ServerErrorHttpException;
use SystemBundle\Model\SystemUploadImageModel;

class SystemUploadImageRepository extends Repository
{
    #[Inject]
    protected SystemUploadImageModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['img_id', 'img_storage', 'img_name', 'img_type', 'img_url', 'img_brief', 'img_size', 'created_at', 'updated_at'];

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemUploadImageModel
    {
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
