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
namespace ThirdPartyBundle\Controller\Backend;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use ThirdPartyBundle\Service\ThirdPartyRequestLogService;

class ThirdPartyRequestLogController extends AbstractController
{
    #[Inject]
    protected ThirdPartyRequestLogService $service;

    public function actionEnumMethod(): ResponseInterface
    {
        $data = $this->service->getRepository()->enumMethod();
        return $this->response->success(data: [
            'default' => $this->service->getRepository()->enumMethodDefault(),
            'list' => $data,
        ]);
    }

    public function actionEnumStatus(): ResponseInterface
    {
        $data = $this->service->getRepository()->enumStatus();
        return $this->response->success(data: [
            'default' => $this->service->getRepository()->enumStatusDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'method' => 'required',
            'host' => 'required',
            'path' => 'required',
            'params' => 'required',
            'status_code' => 'required',
            'status' => 'required',
            'transfer_time' => 'required',
            'result' => 'required',
        ];
        $messages = [
            'method.required' => 'method 参数必填',
            'host.required' => 'host 参数必填',
            'path.required' => 'path 参数必填',
            'params.required' => 'params 参数必填',
            'status_code.required' => 'status_code 参数必填',
            'status.required' => 'status 参数必填',
            'transfer_time.required' => 'transfer_time 参数必填',
            'result.required' => 'result 参数必填',
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
            'filter.id' => 'required',
            'params' => 'required|array',
            'params.method' => 'required',
            'params.host' => 'required',
            'params.path' => 'required',
            'params.params' => 'required',
            'params.status_code' => 'required',
            'params.status' => 'required',
            'params.transfer_time' => 'required',
            'params.result' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.id.required' => 'filter.id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.method.required' => 'params.method 参数必填',
            'params.host.required' => 'params.host 参数必填',
            'params.path.required' => 'params.path 参数必填',
            'params.params.required' => 'params.params 参数必填',
            'params.status_code.required' => 'params.status_code 参数必填',
            'params.status.required' => 'params.status 参数必填',
            'params.transfer_time.required' => 'params.transfer_time 参数必填',
            'params.result.required' => 'params.result 参数必填',
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
