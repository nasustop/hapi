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
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemMenuService;

class SystemMenuController extends AbstractController
{
    #[Inject]
    protected SystemMenuService $service;

    public function actionEnumMenuType(): ResponseInterface
    {
        $data = $this->service->getRepository()->enumMenuType();
        return $this->response->success(data: [
            'default' => $this->service->getRepository()->enumMenuTypeDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'parent_id' => 'required',
            'menu_name' => 'required',
            'menu_alias' => 'required',
            'sort' => 'required',
            'is_show' => 'required',
            'menu_type' => 'required',
        ];
        $messages = [
            'parent_id.required' => 'parent_id 参数必填',
            'menu_name.required' => 'menu_name 参数必填',
            'menu_alias.required' => 'menu_alias 参数必填',
            'sort.required' => 'sort 参数必填',
            'is_show.required' => 'is_show 参数必填',
            'menu_type.required' => 'menu_type 参数必填',
        ];
        $validator = $this->validatorFactory->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->service->saveData(data: $params);

        return $this->response->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->getInfo(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'filter' => 'required|array',
            'filter.menu_id' => 'required',
            'params' => 'required|array',
            'params.parent_id' => 'required',
            'params.menu_name' => 'required',
            'params.menu_alias' => 'required',
            'params.sort' => 'required',
            'params.is_show' => 'required',
            'params.menu_type' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.menu_id.required' => 'filter.menu_id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.parent_id.required' => 'params.parent_id 参数必填',
            'params.menu_name.required' => 'params.menu_name 参数必填',
            'params.menu_alias.required' => 'params.menu_alias 参数必填',
            'params.sort.required' => 'params.sort 参数必填',
            'params.is_show.required' => 'params.is_show 参数必填',
            'params.menu_type.required' => 'params.menu_type 参数必填',
        ];
        $validator = $this->validatorFactory->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->service->updateOneBy(filter: $params['filter'], data: $params['params']);

        return $this->response->success(data: $result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteOneBy(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->request->all();
        $page = (int) $this->request->input(key: 'page', default: 1);
        $page_size = (int) $this->request->input(key: 'page_size', default: 20);
        $result = $this->service->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->response->success(data: $result);
    }
}
