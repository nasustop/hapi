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
class SystemLoginTest extends HttpTestCase
{
    public function testLogin()
    {
        $response = $this->login();
        $this->assertTrue(isset($response['code']) && $response['code'] === 0);
        echo "\n登录token\n";
        var_dump($response['data']);
    }

    public function testLogout()
    {
        $response = $this->login();
        $token = $response['data']['token'];

        $response = $this->logout($token);
        $this->assertTrue(isset($response['code']) && $response['code'] === 0);
        echo "\n退出登录\n";
        var_dump($token);
        var_dump($response['data']);
    }

    protected function login()
    {
        $uri = '/api/backend/login';
        $data = [
            'user_name' => 'compose_test79591',
            'password' => 'compose_test44622',
        ];
        return $this->post($uri, $data);
    }

    protected function logout($token)
    {
        $uri = '/api/backend/logout';
        $header = [
            'Authorization' => 'Bear ' . $token,
        ];
        return $this->post($uri, [], $header);
    }
}
