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
namespace WechatBundle\Controller;

use App\Controller\AbstractController;
use WechatBundle\Kernel\MiniApp;

class TestController extends AbstractController
{
    public function actionTest()
    {
        $js_code = $this->request->input('js_code');
        $config = [
            'app_id' => env('app_id'),
            'secret' => env('app_secret'),
        ];
        $miniApp = new MiniApp($config);
        $result = $miniApp->jscode2session($js_code);
        return $this->response->success($result);
    }
}
