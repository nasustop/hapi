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
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUserService;

class SystemUserController extends AbstractController
{
    #[Inject]
    protected SystemUserService $service;

    /**
     * @throws Exception
     */
    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'user_name' => 'required',
            'login_name' => 'required',
            'password' => 'required',
            'mobile' => 'required',
        ];
        $messages = [
            'user_name.required' => 'user_name 参数必填',
            'login_name.required' => 'login_name 参数必填',
            'password.required' => 'password 参数必填',
            'mobile.required' => 'mobile 参数必填',
        ];
        $validator = $this->validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->service->createUser($params);

        return $this->response->success($result);
    }

    /**
     * @throws Exception
     */
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

        $result = $this->service->updateUser($params['filter'], $params['params']);

        return $this->response->success($result);
    }

    /**
     * @throws Exception
     */
    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteUser($filter);

        return $this->response->success($result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->request->all();
        $page = $this->request->input('page', 1);
        $page_size = $this->request->input('page_size', 20);
        $result = $this->service->pageUserLists($filter, '*', $page, $page_size);

        return $this->response->success($result);
    }
}
