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
use GoodsBundle\Repository\GoodsCategoryRepository;
use GoodsBundle\Service\GoodsCategoryService;
use GoodsBundle\Template\GoodsCategoryTemplate;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;

class GoodsCategoryController extends AbstractController
{
    protected GoodsCategoryService $service;

    public function actionTemplateList(): ResponseInterface
    {
        $template = $this->getTemplate()->getTableTemplate();
        return $this->getResponse()->success($template);
    }

    public function actionTemplateCreate(): ResponseInterface
    {
        $template = $this->getTemplate()
            ->setParentId($this->getRequest()->input('parent_id', 0))
            ->getCreateFormTemplate();
        return $this->getResponse()->success($template);
    }

    public function actionTemplateUpdate(): ResponseInterface
    {
        $id = $this->getRequest()->input('category_id', 0);
        $info = [];
        if (! empty($id)) {
            $repository = make(GoodsCategoryRepository::class);
            $info = $repository->getInfo(['category_id' => $id]);
        }
        $template = $this->getTemplate()
            ->setParentId($info['parent_id'] ?? 0)
            ->getUpdateFormTemplate();
        return $this->getResponse()->success($template);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'parent_id' => 'required',
            'category_name' => 'required',
            'is_show' => 'required',
        ];
        $messages = [
            'parent_id.required' => 'parent_id 参数必填',
            'category_name.required' => 'category_name 参数必填',
            'is_show.required' => 'is_show 参数必填',
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
            'params.parent_id' => 'required',
            'params.category_name' => 'required',
            'params.is_show' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.category_id.required' => 'filter.category_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.parent_id.required' => 'params.parent_id 参数必填',
            'params.category_name.required' => 'params.category_name 参数必填',
            'params.is_show.required' => 'params.is_show 参数必填',
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
        $result = $this->getService()->getRepository()->findTreeByCategoryIds();

        return $this->getResponse()->success(data: [
            'list' => $result['tree'],
            'total' => 0,
        ]);
    }

    public function actionCascadeList(): ResponseInterface
    {
        $result = $this->getService()->getRepository()->getCategoryCascadeData();

        return $this->getResponse()->success($result);
    }

    /**
     * get Service.
     */
    protected function getService(): GoodsCategoryService
    {
        if (empty($this->service)) {
            $this->service = make(GoodsCategoryService::class);
        }
        return $this->service;
    }

    /**
     * get Template.
     */
    protected function getTemplate(): GoodsCategoryTemplate
    {
        if (empty($this->template)) {
            $this->template = make(GoodsCategoryTemplate::class);
        }
        return $this->template;
    }
}
