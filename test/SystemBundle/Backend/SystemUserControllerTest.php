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

namespace HyperfTest\SystemBundle\Backend;

use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class SystemUserControllerTest extends HttpTestCase
{
    public function testUser()
    {
        $createResponse = $this->create();
        $this->assertTrue(isset($createResponse['code']) && $createResponse['code'] === 0);
        $id = $createResponse['data']['user_id'] ?? 0;
        echo "\n添加数据:{$id}\n";
        var_dump($createResponse);

        $updateResponse = $this->update($id);
        $this->assertTrue(isset($updateResponse['code']) && $updateResponse['code'] === 0);
        echo "\n修改数据\n";
        var_dump($updateResponse);

        $listResponse = $this->lists();
        $this->assertTrue(isset($listResponse['code']) && $listResponse['code'] === 0);
        echo "\n列表数据\n";
        var_dump($listResponse);

        $deleteResponse = $this->delete($id);
        $this->assertTrue(isset($deleteResponse['code']) && $deleteResponse['code'] === 0);
        echo "\n删除数据\n";
        var_dump($deleteResponse);
    }

    public function create()
    {
        $uri = '/api/backend/system/auth/user/create';
        $data = [
            'user_name' => 'compose test user',
            'login_name' => 'compose_test' . mt_rand(0, 99999),
            'password' => 'compose_test' . mt_rand(0, 99999),
            'mobile' => 'compose_test' . mt_rand(0, 99999),
            'menu_ids' => [1, 2, 3],
            'role_ids' => [1, 2, 3],
        ];
        return $this->post($uri, $data);
    }

    protected function update($id)
    {
        $uri = '/api/backend/system/auth/user/update';
        $data = [
            'filter' => [
                'user_id' => $id,
            ],
            'params' => [
                'user_name' => 'compose test user',
                'login_name' => 'compose_test' . mt_rand(0, 99999),
                'password' => 'compose_test' . mt_rand(0, 99999),
                'mobile' => 'compose_test' . mt_rand(0, 99999),
                'menu_ids' => [4, 5, 6],
                'role_ids' => [4, 5, 6],
            ],
        ];
        return $this->post($uri, $data);
    }

    protected function delete($id)
    {
        $uri = '/api/backend/system/auth/user/delete';
        $data = [
            'user_id' => $id,
        ];
        return $this->post($uri, $data);
    }

    protected function lists()
    {
        $uri = '/api/backend/system/auth/user/list';
        $query = [
            'page' => 1,
            'page_size' => 10,
        ];
        return $this->get($uri, $query);
    }
}
