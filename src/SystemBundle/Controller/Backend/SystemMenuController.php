<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
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
        return $this->response->success([
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
        $validator = $this->validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->service->saveData($params);

        return $this->response->success($result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->request->all();
        $rules = [
            'filter' => 'required|array',
            'params' => 'required|array',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'params.required' => 'params 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'params.array' => 'params 参数错误，必须是数组格式',
        ];
        $validator = $this->validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $result = $this->service->updateOneBy($params['filter'], $params['params']);

        return $this->response->success($result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteOneBy($filter);

        return $this->response->success($result);
    }

    public function actionList(): ResponseInterface
    {
        $result = $this->service->getRepository()->findTreeByMenuIds();

        return $this->response->success($result);
    }
}
