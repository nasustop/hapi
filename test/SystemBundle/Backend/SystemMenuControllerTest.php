<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
namespace HyperfTest\SystemBundle\Backend;

use HyperfTest\HttpTestCase;
use SystemBundle\Repository\SystemMenuRepository;

/**
 * @internal
 * @coversNothing
 */
class SystemMenuControllerTest extends HttpTestCase
{
    public function testMenu()
    {
        $menuTypeResponse = $this->enumMenuType();
        $this->assertTrue(isset($menuTypeResponse['code']) && $menuTypeResponse['code'] === 0);
        echo "\n菜单类型\n";
        var_dump($menuTypeResponse);

        $createResponse = $this->create();
        $this->assertTrue(isset($createResponse['code']) && $createResponse['code'] === 0);
        $menu_id = $createResponse['data']['menu_id'] ?? 0;
        echo "\n添加数据:{$menu_id}\n";
        var_dump($createResponse);

        $updateResponse = $this->update($menu_id);
        $this->assertTrue(isset($updateResponse['code']) && $updateResponse['code'] === 0);
        echo "\n修改数据\n";
        var_dump($updateResponse);

        $listResponse = $this->lists();
        $this->assertTrue(isset($listResponse['code']) && $listResponse['code'] === 0);
        echo "\n列表数据\n";
        var_dump($listResponse);

        $deleteResponse = $this->delete($menu_id);
        $this->assertTrue(isset($deleteResponse['code']) && $deleteResponse['code'] === 0);
        echo "\n删除数据\n";
        var_dump($deleteResponse);
    }

    protected function enumMenuType()
    {
        $uri = '/api/backend/system/auth/menu/enum/menu_type';
        return $this->get($uri);
    }

    protected function create()
    {
        $uri = '/api/backend/system/auth/menu/create';
        $data = [
            'parent_id' => 0,
            'menu_name' => 'compose test 菜单',
            'menu_alias' => 'compose_test' . mt_rand(0, 99999),
            'sort' => 0,
            'is_show' => 1,
            'menu_type' => SystemMenuRepository::MENU_TYPE_MENU,
        ];
        return $this->post($uri, $data);
    }

    protected function update($menu_id)
    {
        $uri = '/api/backend/system/auth/menu/update';
        $data = [
            'filter' => [
                'menu_id' => $menu_id,
            ],
            'params' => [
                'parent_id' => 0,
                'menu_name' => 'compose test 菜单',
                'menu_alias' => 'compose_test' . mt_rand(0, 99999),
                'sort' => 0,
                'is_show' => 1,
                'menu_type' => SystemMenuRepository::MENU_TYPE_MENU,
            ],
        ];
        return $this->post($uri, $data);
    }

    protected function delete($menu_id)
    {
        $uri = '/api/backend/system/auth/menu/delete';
        $data = [
            'menu_id' => $menu_id,
        ];
        return $this->post($uri, $data);
    }

    protected function lists()
    {
        $uri = '/api/backend/system/auth/menu/list';
        $query = [
            'page' => 1,
            'page_size' => 10,
        ];
        return $this->get($uri, $query);
    }
}
