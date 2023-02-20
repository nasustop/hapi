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
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUploadImageService;

class SystemUploadImageController extends AbstractController
{
    #[Inject]
    protected SystemUploadImageService $service;

    public function actionEnumImgStorage(): ResponseInterface
    {
        $data = $this->service->getRepository()->enumImgStorage();
        return $this->response->success(data: [
            'default' => $this->service->getRepository()->enumImgStorageDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'img_storage' => 'required',
            'img_name' => 'required',
            'img_type' => 'required',
            'img_url' => 'required',
            'img_brief' => 'required',
            'img_size' => 'required',
        ];
        $messages = [
            'img_storage.required' => 'img_storage 参数必填',
            'img_name.required' => 'img_name 参数必填',
            'img_type.required' => 'img_type 参数必填',
            'img_url.required' => 'img_url 参数必填',
            'img_brief.required' => 'img_brief 参数必填',
            'img_size.required' => 'img_size 参数必填',
        ];
        $validator = $this->validatorFactory->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->service->saveData(data: $params);

        return $this->response->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->getInfo(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'filter' => 'required|array',
            'filter.img_id' => 'required',
            'params' => 'required|array',
            'params.img_storage' => 'required',
            'params.img_name' => 'required',
            'params.img_type' => 'required',
            'params.img_url' => 'required',
            'params.img_brief' => 'required',
            'params.img_size' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.img_id.required' => 'filter.img_id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.img_storage.required' => 'params.img_storage 参数必填',
            'params.img_name.required' => 'params.img_name 参数必填',
            'params.img_type.required' => 'params.img_type 参数必填',
            'params.img_url.required' => 'params.img_url 参数必填',
            'params.img_brief.required' => 'params.img_brief 参数必填',
            'params.img_size.required' => 'params.img_size 参数必填',
        ];
        $validator = $this->validatorFactory->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->service->updateOneBy(filter: $params['filter'], data: $params['params']);

        return $this->response->success(data: $result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteOneBy(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->request->all();
        $page = (int) $this->request->input(key: 'page', default: 1);
        $page_size = (int) $this->request->input(key: 'page_size', default: 20);
        $result = $this->service->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->response->success(data: $result);
    }
}
