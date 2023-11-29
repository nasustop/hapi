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
use Hyperf\Validation\Rule;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUserService;

class SystemUserController extends AbstractController
{
    protected SystemUserService $service;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'user_name' => 'required',
            'avatar_url' => 'url',
            'password' => 'required',
            'account' => 'required|unique:system_user_rel_account,rel_type',
            'email' => 'email|unique:system_user_rel_account,rel_type',
            'mobile' => 'unique:system_user_rel_account,rel_type',
        ];
        $messages = [
            'user_name.required' => '用户名称必填',
            'avatar_url.url' => '头像格式错误',
            'password.required' => '密码必填',
            'account.required' => '登陆账号必填',
            'account.unique' => '账号已存在，请更换账号',
            'email.email' => '请输入正确的邮箱格式',
            'email.unique' => '邮箱已存在，请更换邮箱',
            'mobile.unique' => '手机号已存在，请更换手机号',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->createUser(data: $params);

        return $this->getResponse()->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'filter.user_id' => 'required',
            'params' => 'required|array',
            'params.avatar_url' => 'url',
            'params.account' => ['required', Rule::unique('system_user_rel_account', 'rel_type')->ignore($params['filter']['user_id'], 'user_id')],
            'params.email' => ['email', Rule::unique('system_user_rel_account', 'rel_type')->ignore($params['filter']['user_id'], 'user_id')],
            'params.mobile' => Rule::unique('system_user_rel_account', 'rel_type')->ignore($params['filter']['user_id'], 'user_id'),
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.user_id.required' => 'filter.user_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.avatar_url.url' => '头像格式错误',
            'params.account.required' => '登陆账号必填',
            'params.account.unique' => '账号已存在，请更换账号',
            'params.email.email' => '请输入正确的邮箱格式',
            'params.email.unique' => '邮箱已存在，请更换邮箱',
            'params.mobile.unique' => '手机号已存在，请更换手机号',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->updateUser(filter: $params['filter'], data: $params['params']);

        return $this->getResponse()->success(data: $result);
    }

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
            $this->service = make(SystemUserService::class);
        }
        return $this->service;
    }
}
