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
namespace WechatBundle\Kernel;

class MiniApp extends Application
{
    public function jscode2session(string $js_code)
    {
        $result = $this->getClient()->request(
            method: 'get',
            uri: '/sns/jscode2session',
            options: [
                'query' => [
                    'appid' => $this->getAccount()->getAppId(),
                    'secret' => $this->getAccount()->getSecret(),
                    'js_code' => $js_code,
                    'grant_type' => 'authorization_code',
                ],
            ],
        );
        return $result->toArray();
    }
}
