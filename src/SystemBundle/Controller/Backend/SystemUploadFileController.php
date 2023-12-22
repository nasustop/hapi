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
use SystemBundle\Service\SystemUploadFileService;

class SystemUploadFileController extends AbstractController
{
    protected SystemUploadFileService $service;

    public function actionDownloadUploadTemplate(): ResponseInterface
    {
        $file_type = $this->getRequest()->input(key: 'file_type');
        $file_name = $this->getRequest()->input(key: 'file_name', default: '导入模板');

        $rules = [
            'file_type' => 'required',
            'file_name' => 'required',
        ];
        $messages = [
            'file_type.required' => 'file_type 参数必填',
            'file_name.required' => 'file_name 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: [
            'file_type' => $file_type,
            'file_name' => $file_name,
        ], rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->uploadTemplate(fileType: $file_type, fileName: $file_name);
        return $this->getResponse()->success(data: $result);
    }

    public function actionUploadFile(): ResponseInterface
    {
        $file_type = $this->getRequest()->input(key: 'file_type');
        $file_rel_id = (int) $this->getRequest()->input(key: 'file_rel_id');
        $file = $this->getRequest()->file(key: 'upload');
        $auth = $this->getRequest()->getAttribute('auth');

        $rules = [
            'file_type' => 'required',
            'file_rel_id' => 'required',
        ];
        $messages = [
            'file_type.required' => 'file_type 参数必填',
            'file_rel_id.required' => 'file_rel_id 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: [
            'file_type' => $file_type,
            'file_rel_id' => $file_rel_id,
        ], rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $this->getService()->uploadFile(user_id: $auth['user_id'] ?? 0, fileType: $file_type, fileRelID: $file_rel_id, file: $file);

        return $this->getResponse()->success(data: ['status' => true]);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);

        $filter['created_at|gt'] = date('Y-m-d H:i:s', strtotime('-15 day'));
        $orderBy = ['created_at' => 'desc'];
        $result = $this->getService()->pageLists(filter: $filter, page: $page, pageSize: $page_size, orderBy: $orderBy);

        return $this->getResponse()->success(data: $result);
    }

    public function actionExportErrorFile(): ResponseInterface
    {
        $upload_id = (int) $this->getRequest()->input(key: 'upload_id');

        if (empty($upload_id)) {
            throw new BadRequestHttpException(message: 'upload_id 不能为空');
        }

        $result = $this->getService()->exportHandleErrorFile(upload_id: $upload_id);
        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemUploadFileService
    {
        if (empty($this->service)) {
            $this->service = make(SystemUploadFileService::class);
        }
        return $this->service;
    }
}
