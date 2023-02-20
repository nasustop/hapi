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
use SystemBundle\Service\SystemPowerService;

class SystemPowerController extends AbstractController
{
    #[Inject]
    protected SystemPowerService $service;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $result = $this->service->saveData(data: $params);

        return $this->response->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->getInfo(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->request->all();

        $result = $this->service->updateOneBy(filter: $params['filter'], data: $params['params']);

        return $this->response->success(data: $result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteOneBy(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->request->all();
        $page = (int) $this->request->input(key: 'page', default: 1);
        $page_size = (int) $this->request->input(key: 'page_size', default: 20);
        $result = $this->service->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->response->success(data: $result);
    }
}
