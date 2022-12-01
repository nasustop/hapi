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
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUploadImageService;

class SystemUploadImageController extends AbstractController
{
    #[Inject]
    protected SystemUploadImageService $service;

    public function actionUpload(): ResponseInterface
    {
        $file = $this->request->file('upload');
        $storage = $this->request->input('storage', config('file.default', ''));
        $brief = $this->request->input('brief', '');

        if ($file instanceof UploadedFile) {
            $result = $this->service->uploadImg($file, $storage, $brief);
        } else {
            throw new BadRequestHttpException('图片上传错误!');
        }

        return $this->response->success($result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteImage($filter);

        return $this->response->success($result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->request->all();
        $page = (int) $this->request->input('page', 1);
        $page_size = (int) $this->request->input('page_size', 20);
        $orderBy = [
            'created_at' => 'desc',
        ];
        $result = $this->service->pageLists($filter, '*', $page, $page_size, $orderBy);

        return $this->response->success($result);
    }
}
