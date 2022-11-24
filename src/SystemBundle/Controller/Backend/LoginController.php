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
use Nasustop\HapiBase\Auth\AuthManagerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController extends AbstractController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function actionLogin(): ResponseInterface
    {
        $inputData = $this->request->all();
        $auth = $this->container->get(AuthManagerFactory::class);
        $token = $auth->guard('backend')->attempt($inputData);
        return $this->response->success([
            'token' => $token,
        ]);
    }

    public function actionInfo(): ResponseInterface
    {
        return $this->response->success($this->request->getAttribute('auth'));
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function actionLogout(): ResponseInterface
    {
        $auth = $this->container->get(AuthManagerFactory::class);
        $status = $auth->guard('backend')->logout();
        return $this->response->success($status);
    }
}
