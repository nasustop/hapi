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
use SystemBundle\Service\SystemRoleService;

class SystemRoleController extends AbstractController
{
    protected SystemRoleService $service;

    /**
     * @throws \Exception
     */
    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'role_name' => 'required',
            'role_alias' => 'required',
        ];
        $messages = [
            'role_name.required' => 'role_name 参数必填',
            'role_alias.required' => 'role_alias 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->createRole(data: $params);

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
            'filter.role_id' => 'required',
            'params' => 'required|array',
            'params.role_name' => 'required',
            'params.role_alias' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.role_id.required' => 'filter.role_id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.role_name.required' => 'params.role_name 参数必填',
            'params.role_alias.required' => 'params.role_alias 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->updateRole(filter: $params['filter'], data: $params['params']);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * @throws \Exception
     */
    public function actionDelete(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->deleteRole(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);
        $result = $this->getService()->pageRoleLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemRoleService
    {
        if (empty($this->service)) {
            $this->service = make(SystemRoleService::class);
        }
        return $this->service;
    }
}
