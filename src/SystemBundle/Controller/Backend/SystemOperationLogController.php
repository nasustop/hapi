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
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemOperationLogService;
use SystemBundle\Service\SystemUserService;

class SystemOperationLogController extends AbstractController
{
    #[Inject]
    protected SystemOperationLogService $service;

    #[Inject]
    protected SystemUserService $systemUserService;

    public function actionList(): ResponseInterface
    {
        $params = $this->request->all();
        $filter = [];
        if (! empty($params['user_mobile'])) {
            $filter['user_id'] = [];
            $userData = $this->systemUserService->getRepository()->getLists(['mobile|contains' => $params['user_mobile']], 'user_id');
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

        $page = (int) $this->request->input('page', 1);
        $page_size = (int) $this->request->input('page_size', 20);

        $orderBy = [
            'created_at' => 'desc',
        ];

        $result = $this->service->pageLists($filter, '*', $page, $page_size, $orderBy);

        return $this->response->success($result);
    }
}
