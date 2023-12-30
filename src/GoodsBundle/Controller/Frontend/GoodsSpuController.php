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

namespace GoodsBundle\Controller\Frontend;

use App\Controller\AbstractController;
use GoodsBundle\Service\GoodsSpuService;
use GoodsBundle\Template\GoodsSpuTemplate;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;

class GoodsSpuController extends AbstractController
{
    protected GoodsSpuService $service;

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

    public function actionEnumStatus(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumStatus();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumStatusDefault(),
            'list' => $data,
        ]);
    }

    public function actionEnumSpuType(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumSpuType();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumSpuTypeDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'spu_name' => 'required',
            'spu_brief' => 'required',
            'spu_thumb' => 'required',
            'spu_images' => 'required',
            'spu_intro' => 'required',
            'status' => 'required',
            'spu_type' => 'required',
            'price_min' => 'required',
            'price_max' => 'required',
        ];
        $messages = [
            'spu_name.required' => 'spu_name 参数必填',
            'spu_brief.required' => 'spu_brief 参数必填',
            'spu_thumb.required' => 'spu_thumb 参数必填',
            'spu_images.required' => 'spu_images 参数必填',
            'spu_intro.required' => 'spu_intro 参数必填',
            'status.required' => 'status 参数必填',
            'spu_type.required' => 'spu_type 参数必填',
            'price_min.required' => 'price_min 参数必填',
            'price_max.required' => 'price_max 参数必填',
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
            'filter.spu_id' => 'required',
            'params' => 'required|array',
            'params.spu_name' => 'required',
            'params.spu_brief' => 'required',
            'params.spu_thumb' => 'required',
            'params.spu_images' => 'required',
            'params.spu_intro' => 'required',
            'params.status' => 'required',
            'params.spu_type' => 'required',
            'params.price_min' => 'required',
            'params.price_max' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.spu_id.required' => 'filter.spu_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.spu_name.required' => 'params.spu_name 参数必填',
            'params.spu_brief.required' => 'params.spu_brief 参数必填',
            'params.spu_thumb.required' => 'params.spu_thumb 参数必填',
            'params.spu_images.required' => 'params.spu_images 参数必填',
            'params.spu_intro.required' => 'params.spu_intro 参数必填',
            'params.status.required' => 'params.status 参数必填',
            'params.spu_type.required' => 'params.spu_type 参数必填',
            'params.price_min.required' => 'params.price_min 参数必填',
            'params.price_max.required' => 'params.price_max 参数必填',
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
    protected function getService(): GoodsSpuService
    {
        if (empty($this->service)) {
            $this->service = make(GoodsSpuService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): GoodsSpuTemplate
    {
        if (empty($this->template)) {
            $this->template = make(GoodsSpuTemplate::class);
        }
        return $this->template;
    }
}
