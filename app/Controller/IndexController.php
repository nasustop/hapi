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

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function testMemcached()
    {
        $input = $this->request->all();
        memcached()->set($input['key'] ?? 'key', $input['value'] ?? 'value');
        var_dump(memcached()->get($input['key'] ?? 'key'));
        $keys = memcached()->getAllKeys();
        var_dump($keys);
        foreach ($keys as $key) {
            $msg = sprintf("key %s, value %s.\n", $key, memcached()->get($key));
            var_dump($msg);
        }
        return $this->response->json(['code' => 0]);
    }
}
