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
use SystemBundle\Service\SystemApiService;
use SystemBundle\Template\SystemApiTemplate;

class SystemApiController extends AbstractController
{
    protected SystemApiService $service;

    public function actionTemplateList(): ResponseInterface
    {
        $template = $this->getTemplate()->getTableTemplate();
        return $this->getResponse()->success($template);
    }

    public function actionTemplateCreate(): ResponseInterface
    {
        $template = $this->getTemplate()->getCreateFormTemplate();
        return $this->getResponse()->success($template);
    }

    public function actionTemplateUpdate(): ResponseInterface
    {
        $template = $this->getTemplate()->getUpdateFormTemplate();
        return $this->getResponse()->success($template);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'api_name' => 'required',
            'api_alias' => 'required',
            'api_method' => 'required',
            'api_uri' => 'required',
            'api_action' => 'required',
        ];
        $messages = [
            'api_name.required' => 'api_name 参数必填',
            'api_alias.required' => 'api_alias 参数必填',
            'api_method.required' => 'api_method 参数必填',
            'api_uri.required' => 'api_uri 参数必填',
            'api_action.required' => 'api_action 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->saveData(data: $params);

        return $this->getResponse()->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->getInfo(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'filter.api_id' => 'required',
            'params' => 'required|array',
            'params.api_name' => 'required',
            'params.api_alias' => 'required',
            'params.api_method' => 'required',
            'params.api_uri' => 'required',
            'params.api_action' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.api_id.required' => 'filter.api_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.api_name.required' => 'params.api_name 参数必填',
            'params.api_alias.required' => 'params.api_alias 参数必填',
            'params.api_method.required' => 'params.api_method 参数必填',
            'params.api_uri.required' => 'params.api_uri 参数必填',
            'params.api_action.required' => 'params.api_action 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->updateOneBy(filter: $params['filter'], data: $params['params']);

        return $this->getResponse()->success(data: $result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->deleteOneBy(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);
        $result = $this->getService()->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemApiService
    {
        if (empty($this->service)) {
            $this->service = make(SystemApiService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): SystemApiTemplate
    {
        if (empty($this->template)) {
            $this->template = make(SystemApiTemplate::class);
        }
        return $this->template;
    }
}
