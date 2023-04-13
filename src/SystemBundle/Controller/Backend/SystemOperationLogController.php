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
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemOperationLogService;
use SystemBundle\Service\SystemUserService;

class SystemOperationLogController extends AbstractController
{
    protected SystemOperationLogService $service;

    protected SystemUserService $systemUserService;

    public function actionList(): ResponseInterface
    {
        $params = $this->getRequest()->all();
        $filter = [];
        if (! empty($params['user_mobile'])) {
            $filter['user_id'] = [];
            $userData = $this->getSystemUserService()->getRepository()->getLists(['mobile|contains' => $params['user_mobile']], 'user_id');
            $userIds = array_column($userData, 'user_id');
            if (! empty($userIds)) {
                $filter['user_id'] = $userIds;
            }
        }

        if (! empty($params['api_name'])) {
            $filter['api_name|contains'] = $params['api_name'];
        }
        if (! empty($params['api_alias'])) {
            $filter['api_alias|contains'] = $params['api_alias'];
        }
        if (! empty($params['request_uri'])) {
            $filter['request_uri|contains'] = $params['request_uri'];
        }
        if (! empty($params['params'])) {
            $filter['params|contains'] = $params['params'];
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
    protected function getService(): SystemOperationLogService
    {
        if (empty($this->service)) {
            $this->service = $this->getContainer()->get(SystemOperationLogService::class);
        }
        return $this->service;
    }

    /**
     * get SystemUserService.
     */
    protected function getSystemUserService(): SystemUserService
    {
        if (empty($this->systemUserService)) {
            $this->systemUserService = $this->getContainer()->get(SystemUserService::class);
        }
        return $this->systemUserService;
    }
}
