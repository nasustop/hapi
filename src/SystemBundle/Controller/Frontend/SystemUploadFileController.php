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
use SystemBundle\Service\SystemUploadFileService;

class SystemUploadFileController extends AbstractController
{
    protected SystemUploadFileService $service;

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
            'user_id' => 'required',
            'file_type' => 'required',
            'file_rel_id' => 'required',
            'file_name' => 'required',
            'file_size' => 'required',
            'handle_raw_path' => 'required',
            'handle_error_path' => 'required',
            'handle_status' => 'required',
            'handle_line_num' => 'required',
            'success_line_num' => 'required',
            'error_line_num' => 'required',
            'finish_time' => 'required',
        ];
        $messages = [
            'user_id.required' => 'user_id 参数必填',
            'file_type.required' => 'file_type 参数必填',
            'file_rel_id.required' => 'file_rel_id 参数必填',
            'file_name.required' => 'file_name 参数必填',
            'file_size.required' => 'file_size 参数必填',
            'handle_raw_path.required' => 'handle_raw_path 参数必填',
            'handle_error_path.required' => 'handle_error_path 参数必填',
            'handle_status.required' => 'handle_status 参数必填',
            'handle_line_num.required' => 'handle_line_num 参数必填',
            'success_line_num.required' => 'success_line_num 参数必填',
            'error_line_num.required' => 'error_line_num 参数必填',
            'finish_time.required' => 'finish_time 参数必填',
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
            'filter.id' => 'required',
            'params' => 'required|array',
            'params.user_id' => 'required',
            'params.file_type' => 'required',
            'params.file_rel_id' => 'required',
            'params.file_name' => 'required',
            'params.file_size' => 'required',
            'params.handle_raw_path' => 'required',
            'params.handle_error_path' => 'required',
            'params.handle_status' => 'required',
            'params.handle_line_num' => 'required',
            'params.success_line_num' => 'required',
            'params.error_line_num' => 'required',
            'params.finish_time' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.id.required' => 'filter.id 参数必填',
            'params.required' => 'params 参数必填',
            'params.array' => 'params 参数错误，必须是数组格式',
            'params.user_id.required' => 'params.user_id 参数必填',
            'params.file_type.required' => 'params.file_type 参数必填',
            'params.file_rel_id.required' => 'params.file_rel_id 参数必填',
            'params.file_name.required' => 'params.file_name 参数必填',
            'params.file_size.required' => 'params.file_size 参数必填',
            'params.handle_raw_path.required' => 'params.handle_raw_path 参数必填',
            'params.handle_error_path.required' => 'params.handle_error_path 参数必填',
            'params.handle_status.required' => 'params.handle_status 参数必填',
            'params.handle_line_num.required' => 'params.handle_line_num 参数必填',
            'params.success_line_num.required' => 'params.success_line_num 参数必填',
            'params.error_line_num.required' => 'params.error_line_num 参数必填',
            'params.finish_time.required' => 'params.finish_time 参数必填',
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
    protected function getService(): SystemUploadFileService
    {
        if (empty($this->service)) {
            $this->service = $this->getContainer()->get(SystemUploadFileService::class);
        }
        return $this->service;
    }
}
