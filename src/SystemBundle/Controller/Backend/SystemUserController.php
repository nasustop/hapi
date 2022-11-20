<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
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
use SystemBundle\Service\SystemUserService;

class SystemUserController extends AbstractController
{
    #[Inject]
    protected SystemUserService $service;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'user_name' => 'required',
            'login_name' => 'required',
            'password' => 'required',
            'mobile' => 'required',
            'user_status' => 'required',
        ];
        $messages = [
            'user_name.required' => 'user_name 参数必填',
            'login_name.required' => 'login_name 参数必填',
            'password.required' => 'password 参数必填',
            'mobile.required' => 'mobile 参数必填',
            'user_status.required' => 'user_status 参数必填',
        ];
        $validator = $this->validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->service->saveData($params);

        return $this->response->success($result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->request->all();
        $rules = [
            'filter' => 'required|array',
            'params' => 'required|array',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'params.required' => 'params 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'params.array' => 'params 参数错误，必须是数组格式',
        ];
        $validator = $this->validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->service->updateOneBy($params['filter'], $params['params']);

        return $this->response->success($result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteOneBy($filter);

        return $this->response->success($result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->getInfo($filter);

        return $this->response->success($result);
    }

    public function actionList(): ResponseInterface
    {
        $params = $this->request->all();
        $rules = [
            'filter' => 'required|array',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
        ];
        $validator = $this->validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }
        $page = $this->request->input('page', 1);
        $page_size = $this->request->input('page_size', 20);
        $result = $this->service->pageLists($params['filter'], '*', $page, $page_size);

        return $this->response->success($result);
    }
}
