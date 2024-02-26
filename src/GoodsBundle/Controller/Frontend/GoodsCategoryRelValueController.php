<?php

declare(strict_types=1);

namespace GoodsBundle\Controller\Frontend;

use App\Controller\AbstractController;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use GoodsBundle\Service\GoodsCategoryRelValueService;
use GoodsBundle\Template\GoodsCategoryRelValueTemplate;

class GoodsCategoryRelValueController extends AbstractController
{
    protected GoodsCategoryRelValueService $service;

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

    
    public function actionEnumRelTypeType(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumRelTypeType();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumRelTypeTypeDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
			'rel_type_type' => 'required',
			'rel_id' => 'required',
		];
		$messages = [
			'rel_type_type.required' => 'rel_type_type 参数必填',
			'rel_id.required' => 'rel_id 参数必填',
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
			'filter.category_id' => 'required',
			'params' => 'required|array',
			'params.rel_type_type' => 'required',
			'params.rel_id' => 'required',
		];
		$messages = [
			'filter.required' => 'filter 参数必填',
			'filter.array' => 'filter 参数错误，必须是数组格式',
			'filter.category_id.required' => 'filter.category_id 参数必填',
			'params.required' => 'params 参数必填',
			'params.array' => 'params 参数错误，必须是数组格式',
			'params.rel_type_type.required' => 'params.rel_type_type 参数必填',
			'params.rel_id.required' => 'params.rel_id 参数必填',
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
    protected function getService(): GoodsCategoryRelValueService
    {
        if (empty($this->service)) {
            $this->service = make(GoodsCategoryRelValueService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): GoodsCategoryRelValueTemplate
    {
        if (empty($this->template)) {
            $this->template = make(GoodsCategoryRelValueTemplate::class);
        }
        return $this->template;
    }
}
