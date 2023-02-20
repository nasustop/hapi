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
use SystemBundle\Service\SystemOperationLogService;

class SystemOperationLogController extends AbstractController
{
    #[Inject]
    protected SystemOperationLogService $service;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'user_id' => 'required',
            'from_ip' => 'required',
            'request_uri' => 'required',
            'request_method' => 'required',
            'api_alias' => 'required',
            'api_name' => 'required',
            'params' => 'required',
        ];
        $messages = [
            'user_id.required' => 'user_id 参数必填',
            'from_ip.required' => 'from_ip 参数必填',
            'request_uri.required' => 'request_uri 参数必填',
            'request_method.required' => 'request_method 参数必填',
            'api_alias.required' => 'api_alias 参数必填',
            'api_name.required' => 'api_name 参数必填',
            'params.required' => 'params 参数必填',
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
            'filter.log_id' => 'required',
            'params' => 'required|array',
            'params.user_id' => 'required',
            'params.from_ip' => 'required',
            'params.request_uri' => 'required',
            'params.request_method' => 'required',
            'params.api_alias' => 'required',
            'params.api_name' => 'required',
            'params.params' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.log_id.required' => 'filter.log_id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.user_id.required' => 'params.user_id 参数必填',
            'params.from_ip.required' => 'params.from_ip 参数必填',
            'params.request_uri.required' => 'params.request_uri 参数必填',
            'params.request_method.required' => 'params.request_method 参数必填',
            'params.api_alias.required' => 'params.api_alias 参数必填',
            'params.api_name.required' => 'params.api_name 参数必填',
            'params.params.required' => 'params.params 参数必填',
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
