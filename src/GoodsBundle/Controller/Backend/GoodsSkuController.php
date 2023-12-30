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
use GoodsBundle\Service\GoodsSkuService;
use GoodsBundle\Template\GoodsSkuTemplate;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;

class GoodsSkuController extends AbstractController
{
    protected GoodsSkuService $service;

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
            'spu_id' => 'required',
            'sku_thumb' => 'required',
            'sale_price' => 'required',
            'market_price' => 'required',
            'sku_code' => 'required',
            'stock_num' => 'required',
            'sold_num' => 'required',
            'sort' => 'required',
        ];
        $messages = [
            'spu_id.required' => 'spu_id 参数必填',
            'sku_thumb.required' => 'sku_thumb 参数必填',
            'sale_price.required' => 'sale_price 参数必填',
            'market_price.required' => 'market_price 参数必填',
            'sku_code.required' => 'sku_code 参数必填',
            'stock_num.required' => 'stock_num 参数必填',
            'sold_num.required' => 'sold_num 参数必填',
            'sort.required' => 'sort 参数必填',
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
            'filter.sku_id' => 'required',
            'params' => 'required|array',
            'params.spu_id' => 'required',
            'params.sku_thumb' => 'required',
            'params.sale_price' => 'required',
            'params.market_price' => 'required',
            'params.sku_code' => 'required',
            'params.stock_num' => 'required',
            'params.sold_num' => 'required',
            'params.sort' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.sku_id.required' => 'filter.sku_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.spu_id.required' => 'params.spu_id 参数必填',
            'params.sku_thumb.required' => 'params.sku_thumb 参数必填',
            'params.sale_price.required' => 'params.sale_price 参数必填',
            'params.market_price.required' => 'params.market_price 参数必填',
            'params.sku_code.required' => 'params.sku_code 参数必填',
            'params.stock_num.required' => 'params.stock_num 参数必填',
            'params.sold_num.required' => 'params.sold_num 参数必填',
            'params.sort.required' => 'params.sort 参数必填',
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
    protected function getService(): GoodsSkuService
    {
        if (empty($this->service)) {
            $this->service = make(GoodsSkuService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): GoodsSkuTemplate
    {
        if (empty($this->template)) {
            $this->template = make(GoodsSkuTemplate::class);
        }
        return $this->template;
    }
}
