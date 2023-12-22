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

namespace HyperfTest\WechatBundle;

use HyperfTest\HttpTestCase;
use WechatBundle\Kernel\MiniApp;

/**
 * @internal
 * @coversNothing
 */
class MiniAppTest extends HttpTestCase
{
    public function testAccessToken()
    {
        $config = [
            'app_id' => env('APP_ID'),
            'secret' => env('APP_SECRET'),
        ];
        $miniApp = new MiniApp($config);
        $token = $miniApp->getAccessToken()->getToken();
        var_dump($token);
    }

    public function testJsCode()
    {
        $js_code = '123123';
        $config = [
            'app_id' => env('APP_ID'),
            'secret' => env('APP_SECRET'),
        ];
        $miniApp = new MiniApp($config);
        $result = $miniApp->jscode2session($js_code);
        var_dump($result);
    }
}
