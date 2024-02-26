<?php

declare(strict_types=1);

namespace GoodsBundle\Controller\Backend;

use App\Controller\AbstractController;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use GoodsBundle\Service\GoodsParamService;
use GoodsBundle\Template\GoodsParamTemplate;

class GoodsParamController extends AbstractController
{
    protected GoodsParamService $service;

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
			'params_name' => 'required',
			'sort' => 'required',
            'params_value' => 'required',
            'params_value.*.params_value_name' => 'required',
		];
		$messages = [
			'params_name.required' => 'params_name 参数必填',
			'sort.required' => 'sort 参数必填',
            'params_value.required' => 'params_value 参数必填',
            'params_value.*.params_value_name.required' => 'params_value_name 参数必填',
		];
		$validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

		if ($validator->fails()) {
			throw new BadRequestHttpException(message: $validator->errors()->first());
		}

        $result = $this->getService()->createGoodsParams(data: $params);

        return $this->getResponse()->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->getGoodsParamsInfo(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
			'filter' => 'required|array',
			'filter.params_id' => 'required',
			'params' => 'required|array',
			'params.params_name' => 'required',
			'params.sort' => 'required',
            'params.params_value' => 'required',
            'params.params_value.*.params_value_name' => 'required',
		];
		$messages = [
			'filter.required' => 'filter 参数必填',
			'filter.array' => 'filter 参数错误，必须是数组格式',
			'filter.params_id.required' => 'filter.params_id 参数必填',
			'params.required' => 'params 参数必填',
			'params.array' => 'params 参数错误，必须是数组格式',
			'params.params_name.required' => 'params.params_name 参数必填',
			'params.sort.required' => 'params.sort 参数必填',
            'params.params_value.required' => 'params_value 参数必填',
            'params.params_value.*.params_value_name.required' => 'params_value_name 参数必填',
		];
		$validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

		if ($validator->fails()) {
			throw new BadRequestHttpException(message: $validator->errors()->first());
		}

        $result = $this->getService()->updateGoodsParams(filter: $params['filter'], data: $params['params']);

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
    protected function getService(): GoodsParamService
    {
        if (empty($this->service)) {
            $this->service = make(GoodsParamService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): GoodsParamTemplate
    {
        if (empty($this->template)) {
            $this->template = make(GoodsParamTemplate::class);
        }
        return $this->template;
    }
}
