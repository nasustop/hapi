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
use SystemBundle\Service\SystemExportFileService;
use SystemBundle\Traits\ExportTypeServiceTrait;

class SystemExportFileController extends AbstractController
{
    use ExportTypeServiceTrait;

    protected SystemExportFileService $service;

    public function actionExport(): ResponseInterface
    {
        $params = $this->getRequest()->all();
        $auth = $this->getRequest()->getAttribute('auth');
        $params['user_id'] = $auth['user_id'];

        $rules = [
            'user_id' => 'required',
            'export_type' => 'required',
            'request_data' => 'required',
        ];
        $messages = [
            'user_id.required' => 'user_id 参数必填',
            'export_type.required' => 'export_type 参数必填',
            'request_data.required' => 'request_data 参数必填',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }
        $useQueue = (bool) $this->getRequest()->input('useQueue', true);

        $result = $this->getService()->exportFile(user_id: $params['user_id'], export_type: $params['export_type'], request_data: $params['request_data'], useQueue: $useQueue);

        return $this->getResponse()->success(data: $result);
    }

    public function actionExportTypeList(): ResponseInterface
    {
        return $this->getResponse()->success(data: $this->getExportTypeList());
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);

        $orderBy = ['created_at' => 'desc'];

        $result = $this->getService()->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size, orderBy: $orderBy);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemExportFileService
    {
        if (empty($this->service)) {
            $this->service = make(SystemExportFileService::class);
        }
        return $this->service;
    }
}
