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
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemMenuService;

class SystemMenuController extends AbstractController
{
    protected SystemMenuService $service;

    public function actionEnumMenuType(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumMenuType();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumMenuTypeDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

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
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->saveData(data: $params);

        return $this->getResponse()->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'filter.menu_id' => 'required',
            'params' => 'required|array',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.menu_id.required' => 'filter.menu_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
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
        $result = $this->getService()->getRepository()->findTreeByMenuIds();

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemMenuService
    {
        if (empty($this->service)) {
            $this->service = $this->getContainer()->get(SystemMenuService::class);
        }
        return $this->service;
    }
}
