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
namespace SystemBundle\Controller\Frontend;

use App\Controller\AbstractController;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemUploadFileMessageService;

class SystemUploadFileMessageController extends AbstractController
{
    protected SystemUploadFileMessageService $service;

    public function actionEnumHandleStatus(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumHandleStatus();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumHandleStatusDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'upload_id' => 'required',
            'line_num' => 'required',
            'raw_data' => 'required',
            'handle_status' => 'required',
            'error_message' => 'required',
        ];
        $messages = [
            'upload_id.required' => 'upload_id 参数必填',
            'line_num.required' => 'line_num 参数必填',
            'raw_data.required' => 'raw_data 参数必填',
            'handle_status.required' => 'handle_status 参数必填',
            'error_message.required' => 'error_message 参数必填',
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
            'filter.' => 'required',
            'params' => 'required|array',
            'params.upload_id' => 'required',
            'params.line_num' => 'required',
            'params.raw_data' => 'required',
            'params.handle_status' => 'required',
            'params.error_message' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter..required' => 'filter. 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.upload_id.required' => 'params.upload_id 参数必填',
            'params.line_num.required' => 'params.line_num 参数必填',
            'params.raw_data.required' => 'params.raw_data 参数必填',
            'params.handle_status.required' => 'params.handle_status 参数必填',
            'params.error_message.required' => 'params.error_message 参数必填',
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
    protected function getService(): SystemUploadFileMessageService
    {
        if (empty($this->service)) {
            $this->service = $this->getContainer()->get(SystemUploadFileMessageService::class);
        }
        return $this->service;
    }
}
