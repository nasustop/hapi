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
use GoodsBundle\Request\GoodsSpuRequest;
use GoodsBundle\Service\GoodsSpuService;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Rule;
use Psr\Http\Message\ResponseInterface;

class GoodsSpuController extends AbstractController
{
    protected GoodsSpuService $service;

    protected GoodsSpuRequest $goodsSpuRequest;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $this->getGoodsSpuRequest()->validGoodsSpuParams($this->getValidatorFactory(), $params);

        $result = $this->getService()->createGoodsSpu($params);

        return $this->getResponse()->success($result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->getGoodsSpuInfo($filter);

        return $this->getResponse()->success($result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();
        $this->getGoodsSpuRequest()->validGoodsSpuParams($this->getValidatorFactory(), $params['params']);

        $result = $this->getService()->updateGoodsSpu($params['filter'], $params['params']);

        return $this->getResponse()->success($result);
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

    /**
     * get GoodsSpuRequest.
     */
    protected function getGoodsSpuRequest(): GoodsSpuRequest
    {
        if (empty($this->goodsSpuRequest)) {
            $this->goodsSpuRequest = make(GoodsSpuRequest::class);
        }
        return $this->goodsSpuRequest;
    }

}
