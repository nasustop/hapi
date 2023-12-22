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
use Psr\Http\Message\ResponseInterface;
use ThirdPartyBundle\Service\ThirdPartyRequestLogService;

class ThirdPartyRequestLogController extends AbstractController
{
    protected ThirdPartyRequestLogService $service;

    public function actionEnumMethod(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumMethod();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumMethodDefault(),
            'list' => $data,
        ]);
    }

    public function actionEnumStatus(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumStatus();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumStatusDefault(),
            'list' => $data,
        ]);
    }

    public function actionEnumStatusCode(): ResponseInterface
    {
        $data = $this->getService()->getRepository()->enumStatusCode();
        return $this->getResponse()->success(data: [
            'default' => $this->getService()->getRepository()->enumStatusCodeDefault(),
            'list' => $data,
        ]);
    }

    public function actionList(): ResponseInterface
    {
        $params = $this->getRequest()->all();

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

        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);

        $orderBy = [
            'created_at' => 'desc',
        ];

        $result = $this->getService()->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size, orderBy: $orderBy);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): ThirdPartyRequestLogService
    {
        if (empty($this->service)) {
            $this->service = make(ThirdPartyRequestLogService::class);
        }
        return $this->service;
    }
}
