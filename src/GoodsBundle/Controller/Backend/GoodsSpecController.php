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

namespace GoodsBundle\Controller\Backend;

use App\Controller\AbstractController;
use GoodsBundle\Repository\GoodsSpecRepository;
use GoodsBundle\Service\GoodsSpecService;
use GoodsBundle\Template\GoodsSpecTemplate;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Rule;
use Psr\Http\Message\ResponseInterface;

class GoodsSpecController extends AbstractController
{
    protected GoodsSpecService $service;

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
            'spec_name' => 'required',
            'sort' => 'required',
            'show_type' => ['required', Rule::in(array_values(GoodsSpecRepository::ENUM_SHOW_TYPE))],
            'spec_value' => 'required',
        ];
        $messages = [
            'spec_name.required' => 'spec_name 参数必填',
            'sort.required' => 'sort 参数必填',
            'show_type.required' => 'show_type 参数必填',
            'show_type.in' => 'show_type 参数必须是[' . implode(',', array_values(GoodsSpecRepository::ENUM_SHOW_TYPE)) . ']中的一个',
            'spec_value.required' => 'spec_value 参数必填',
        ];
        switch ($params['show_type']) {
            case GoodsSpecRepository::ENUM_SHOW_TYPE_IMG:
                $rules['spec_value.*.spec_value_img'] = 'required';
                $messages['spec_value.*.spec_value_img.required'] = '规格属性图片必填';
                break;
            case GoodsSpecRepository::ENUM_SHOW_TYPE_TEXT:
                $rules['spec_value.*.spec_value_name'] = 'required';
                $messages['spec_value.*.spec_value_name.required'] = '规格属性名称必填';
                break;
            case GoodsSpecRepository::ENUM_SHOW_TYPE_ALL:
                $rules['spec_value.*.spec_value_img'] = 'required';
                $messages['spec_value.*.spec_value_img.required'] = '规格属性图片必填';
                $rules['spec_value.*.spec_value_name'] = 'required';
                $messages['spec_value.*.spec_value_name.required'] = '规格属性名称必填';
                break;
        }
        $validator = $this->getValidatorFactory()->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->getService()->createGoodsSpec($params);

        return $this->getResponse()->success($result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->getGoodsSpecInfo($filter);

        return $this->getResponse()->success($result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'filter.spec_id' => 'required',
            'params' => 'required|array',
            'params.spec_name' => 'required',
            'params.sort' => 'required',
            'params.show_type' => ['required', Rule::in(array_values(GoodsSpecRepository::ENUM_SHOW_TYPE))],
            'params.spec_value' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.spec_id.required' => 'filter.spec_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.spec_name.required' => 'params.spec_name 参数必填',
            'params.sort.required' => 'params.sort 参数必填',
            'params.show_type.required' => 'params.show_type 参数必填',
            'params.show_type.in' => 'show_type 参数必须是[' . implode(',', array_values(GoodsSpecRepository::ENUM_SHOW_TYPE)) . ']中的一个',
            'params.spec_value.required' => 'spec_value 参数必填',
        ];
        switch ($params['params']['show_type']) {
            case GoodsSpecRepository::ENUM_SHOW_TYPE_IMG:
                $rules['params.spec_value.*.spec_value_img'] = 'required';
                $messages['params.spec_value.*.spec_value_img.required'] = '规格属性图片必填';
                break;
            case GoodsSpecRepository::ENUM_SHOW_TYPE_TEXT:
                $rules['params.spec_value.*.spec_value_name'] = 'required';
                $messages['params.spec_value.*.spec_value_name.required'] = '规格属性名称必填';
                break;
            case GoodsSpecRepository::ENUM_SHOW_TYPE_ALL:
                $rules['params.spec_value.*.spec_value_img'] = 'required';
                $messages['params.spec_value.*.spec_value_img.required'] = '规格属性图片必填';
                $rules['params.spec_value.*.spec_value_name'] = 'required';
                $messages['params.spec_value.*.spec_value_name.required'] = '规格属性名称必填';
                break;
        }
        $validator = $this->getValidatorFactory()->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->getService()->updateGoodsSpec($params['filter'], $params['params']);

        return $this->getResponse()->success($result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->deleteOneBy($filter);

        return $this->getResponse()->success($result);
    }

    public function actionList(): ResponseInterface
    {
        $page = (int) $this->getRequest()->input('page', 1);
        $page_size = (int) $this->getRequest()->input('page_size', 20);
        $filter = [];
        $spec_name = $this->getRequest()->input('spec_name');
        if (! empty($spec_name)) {
            $filter['spec_name|contains'] = $spec_name;
        }
        $orderBy = [
            'sort' => 'desc',
        ];
        $result = $this->getService()->getGoodsSpecList($filter, '*', $page, $page_size, $orderBy);

        return $this->getResponse()->success($result);
    }

    public function actionValueList(): ResponseInterface
    {
        $spec_value_id = $this->getRequest()->input('spec_value_id');
        $result = $this->getService()->getGoodsSpecValueList($spec_value_id);

        return $this->getResponse()->success($result);
    }

    /**
     * get Service.
     */
    protected function getService(): GoodsSpecService
    {
        if (empty($this->service)) {
            $this->service = make(GoodsSpecService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): GoodsSpecTemplate
    {
        if (empty($this->template)) {
            $this->template = make(GoodsSpecTemplate::class);
        }
        return $this->template;
    }
}
