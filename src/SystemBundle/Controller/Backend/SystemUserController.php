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
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUserService;

class SystemUserController extends AbstractController
{
    protected SystemUserService $service;

    /**
     * @throws \Exception
     */
    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

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
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->createUser(data: $params);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * @throws \Exception
     */
    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'filter.user_id' => 'required',
            'params' => 'required|array',
            'params.user_name' => 'required',
            'params.login_name' => 'required',
            'params.mobile' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.user_id.required' => 'filter.user_id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.user_name.required' => 'params.user_name 参数必填',
            'params.login_name.required' => 'params.login_name 参数必填',
            'params.mobile.required' => 'params.mobile 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->updateUser(filter: $params['filter'], data: $params['params']);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * @throws \Exception
     */
    public function actionDelete(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->deleteUser(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);
        $result = $this->getService()->pageUserLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemUserService
    {
        if (empty($this->service)) {
            $this->service = $this->getContainer()->get(SystemUserService::class);
        }
        return $this->service;
    }
}
