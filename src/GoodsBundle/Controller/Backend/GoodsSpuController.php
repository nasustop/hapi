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
use GoodsBundle\Repository\GoodsSpuRepository;
use GoodsBundle\Service\GoodsSpuService;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Rule;
use Psr\Http\Message\ResponseInterface;

class GoodsSpuController extends AbstractController
{
    protected GoodsSpuService $service;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'spu_name' => 'required',
            'category_ids' => 'required',
            'spu_thumb' => 'required',
            'spu_images' => 'required',
            'price_min' => 'required',
        ];
        $messages = [
            'spu_name.required' => 'spu_name 参数必填',
            'category_ids.required' => 'category_ids 参数必填',
            'spu_thumb.required' => 'spu_thumb 参数必填',
            'spu_images.required' => 'spu_images 参数必填',
            'price_min.required' => 'price_min 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->createGoodsSpu($params);

        return $this->getResponse()->success($result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->getGoodsSpuInfo(filter: $filter);

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
            'params.spu_thumb' => 'required',
            'params.spu_images' => 'required',
            'params.spu_intro' => 'required',
            'params.price_min' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.spu_id.required' => 'filter.spu_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.spu_name.required' => 'params.spu_name 参数必填',
            'params.spu_thumb.required' => 'params.spu_thumb 参数必填',
            'params.spu_images.required' => 'params.spu_images 参数必填',
            'params.spu_intro.required' => 'params.spu_intro 参数必填',
            'params.price_min.required' => 'params.price_min 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->updateGoodsSpu($params['filter'], $params['params']);

        return $this->getResponse()->success(data: $result);
    }

    public function actionUpdateSome(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'params' => 'required|array',
            'params.status' => Rule::in(GoodsSpuRepository::ENUM_STATUS),
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.status.in' => 'params.status 参数错误',
        ];
        $validator = $this->getValidatorFactory()->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->getService()->updateOneBy($params['filter'], $params['params']);

        return $this->getResponse()->success($result);
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
}
