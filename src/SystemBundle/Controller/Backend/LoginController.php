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
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Nasustop\HapiAuth\AuthManagerFactory;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemMenuService;
use SystemBundle\Service\SystemUserService;

class LoginController extends AbstractController
{
    protected SystemUserService $service;

    protected SystemMenuService $menuService;

    public function actionLogin(): ResponseInterface
    {
        $inputData = $this->getRequest()->all();
        /** @var AuthManagerFactory $auth */
        $auth = make(AuthManagerFactory::class);
        $token = $auth->guard(guard: 'backend')->attempt(inputData: $inputData);
        return $this->getResponse()->success(data: [
            'token' => $token,
        ]);
    }

    public function actionInfo(): ResponseInterface
    {
        $authInfo = $this->getRequest()->getAttribute(name: 'auth');
        if (empty($authInfo) || empty($authInfo['user_id'])) {
            throw new UnauthorizedHttpException(message: 'auth获取失败，请重新登录');
        }
        $result = $this->getService()->getInfo(['user_id' => $authInfo['user_id']]);
        if (empty($result)) {
            throw new UnauthorizedHttpException(message: '账号不存在，请联系管理员');
        }
        $menu_ids = $this->getService()->getUserAllMenuIds($result['user_id']);
        if (! $authInfo['is_support_user'] && empty($menu_ids)) {
            throw new UnauthorizedHttpException(message: '当前账号没有配置后台管理权限,请联系管理员');
        }
        $menuData = $this->getMenuService()->getRepository()->findTreeByMenuIds(menu_ids: $menu_ids);
        $result['is_support_user'] = $authInfo['is_support_user'];
        $result['menu_alias'] = array_values(array_filter(array_unique(array_column($menuData['list'] ?? [], 'menu_alias'))));
        return $this->getResponse()->success(data: $result);
    }

    public function actionLogout(): ResponseInterface
    {
        /** @var AuthManagerFactory $auth */
        $auth = make(AuthManagerFactory::class);
        $status = $auth->guard(guard: 'backend')->logout();
        return $this->getResponse()->success(data: $status);
    }

    public function actionRefresh(): ResponseInterface
    {
        /** @var AuthManagerFactory $auth */
        $auth = make(AuthManagerFactory::class);
        $token = $auth->guard(guard: 'backend')->refresh();
        return $this->getResponse()->success(data: [
            'token' => $token,
        ]);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemUserService
    {
        if (empty($this->service)) {
            $this->service = make(SystemUserService::class);
        }
        return $this->service;
    }

    /**
     * get MenuService.
     */
    protected function getMenuService(): SystemMenuService
    {
        if (empty($this->menuService)) {
            $this->menuService = make(SystemMenuService::class);
        }
        return $this->menuService;
    }
}
