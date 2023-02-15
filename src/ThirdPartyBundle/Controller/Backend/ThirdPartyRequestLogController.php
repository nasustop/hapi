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
namespace ThirdPartyBundle\Controller\Backend;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use ThirdPartyBundle\Service\ThirdPartyRequestLogService;

class ThirdPartyRequestLogController extends AbstractController
{
    #[Inject]
    protected ThirdPartyRequestLogService $service;

    public function actionEnumRequestMethod()
    {
        $list = [
            'GET' => 'GET',
            'POST' => 'POST',
            'PUT' => 'PUT',
            'DELETE' => 'DELETE',
        ];
        $default = '';
        return $this->response->success([
            'default' => $default,
            'list' => $list,
        ]);
    }

    public function actionEnumRequestStatus()
    {
        $list = [
            'success' => '成功',
            'fail' => '失败',
        ];
        $default = '';
        return $this->response->success([
            'default' => $default,
            'list' => $list,
        ]);
    }

    public function actionEnumRequestStatusCode()
    {
        $list = [
            200 => 200,
            400 => 400,
            401 => 401,
            403 => 403,
            404 => 404,
            500 => 500,
        ];
        $default = '';
        return $this->response->success([
            'default' => $default,
            'list' => $list,
        ]);
    }

    public function actionList(): ResponseInterface
    {
        $params = $this->request->all();

        $filter = [];
        if (! empty($params['method'])) {
            $filter['method'] = $params['method'];
        }
        if (! empty($params['status'])) {
            $filter['status'] = $params['status'];
        }
        if (! empty($params['status_code'])) {
            $filter['status_code'] = $params['status_code'];
        }
        if (! empty($params['host'])) {
            $filter['host|contains'] = $params['host'];
        }
        if (! empty($params['path'])) {
            $filter['path|contains'] = $params['path'];
        }
        if (! empty($params['params'])) {
            $filter['params|contains'] = $params['params'];
        }
        if (! empty($params['result'])) {
            $filter['result|contains'] = $params['result'];
        }
        if (! empty($params['time_start_begin'])) {
            $filter['created_at|>='] = date('Y-m-d H:i:s', (int) $params['time_start_begin']);
        }
        if (! empty($params['time_start_end'])) {
            $filter['created_at|<='] = date('Y-m-d H:i:s', (int) $params['time_start_end']);
        }

        $page = (int) $this->request->input(key: 'page', default: 1);
        $page_size = (int) $this->request->input(key: 'page_size', default: 20);

        $orderBy = [
            'created_at' => 'desc',
        ];

        $result = $this->service->pageLists(filter: $filter, page: $page, pageSize: $page_size, orderBy: $orderBy);

        return $this->response->success(data: $result);
    }
}
