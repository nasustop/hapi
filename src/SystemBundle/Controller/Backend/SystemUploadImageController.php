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
namespace SystemBundle\Controller\Backend;

use App\Controller\AbstractController;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUploadImageService;

class SystemUploadImageController extends AbstractController
{
    protected SystemUploadImageService $service;

    public function actionUpload(): ResponseInterface
    {
        $file = $this->getRequest()->file('upload');
        $storage = $this->getRequest()->input('storage', config('file.default', ''));
        $brief = $this->getRequest()->input('brief', '');

        if ($file instanceof UploadedFile) {
            $result = $this->getService()->uploadImg($file, $storage, $brief);
        } else {
            throw new BadRequestHttpException('图片上传错误!');
        }

        return $this->getResponse()->success($result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->deleteImage(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);
        $orderBy = [
            'created_at' => 'desc',
        ];
        $result = $this->getService()->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size, orderBy: $orderBy);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemUploadImageService
    {
        if (empty($this->service)) {
            $this->service = $this->getContainer()->get(SystemUploadImageService::class);
        }
        return $this->service;
    }
}
