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
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Nasustop\HapiBase\Auth\AuthManagerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemMenuService;
use SystemBundle\Service\SystemUserService;

class LoginController extends AbstractController
{
    #[Inject]
    protected SystemUserService $service;

    #[Inject]
    protected SystemMenuService $menuService;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function actionLogin(): ResponseInterface
    {
        $inputData = $this->request->all();
        $auth = $this->container->get(AuthManagerFactory::class);
        $token = $auth->guard(guard: 'backend')->attempt(inputData: $inputData);
        return $this->response->success([
            'token' => $token,
        ]);
    }

    public function actionInfo(): ResponseInterface
    {
        $authInfo = $this->request->getAttribute('auth');
        if (empty($authInfo) || empty($authInfo['user_id'])) {
            throw new UnauthorizedHttpException('auth获取失败，请重新登录');
        }
        $result = $this->service->getInfo(['user_id' => $authInfo['user_id']]);
        if (empty($result)) {
            throw new UnauthorizedHttpException('账号不存在，请联系管理员');
        }
        $menu_ids = $this->service->getUserAllMenuIds($result['user_id']);
        if (! $authInfo['is_support_user'] && empty($menu_ids)) {
            throw new UnauthorizedHttpException('当前账号没有配置后台管理权限,请联系管理员');
        }
        $menuData = $this->menuService->getRepository()->findTreeByMenuIds(menu_ids: $menu_ids);
        $result['menu_alias'] = array_values(array_filter(array_unique(array_column($menuData['list'] ?? [], 'menu_alias'))));
        return $this->response->success($result);
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
