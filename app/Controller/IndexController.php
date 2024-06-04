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

namespace App\Controller;

use App\Job\InstallJob;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Nasustop\HapiQueue\Producer;
use Psr\Http\Message\ResponseInterface;

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->getRequest()->input('user', 'Hapi');
        $method = $this->getRequest()->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function actionInstall(): ResponseInterface
    {
        $key = 'SYSTEM_INSTALL_STATUS';
        $status = redis()->get($key);
        if ($status === 'running') {
            throw new BadRequestHttpException('正在安装中，请稍后');
        }
        if ($status === 'success') {
            throw new BadRequestHttpException('安装完成');
        }
        redis()->set($key, 'running');
        $job = new InstallJob();
        (new Producer($job))->onQueue('default')->dispatcher();
        return $this->getResponse()->success('正在安装中，请稍后刷新');
    }

    public function actionUninstall(): ResponseInterface
    {
        $key = 'SYSTEM_INSTALL_STATUS';
        redis()->del($key);
        return $this->getResponse()->success('success');
    }
}
