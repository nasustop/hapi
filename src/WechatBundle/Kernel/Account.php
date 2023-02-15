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

class Account
{
    public function __construct(protected string $app_id, protected string $secret)
    {
    }

    /**
     * get AppId.
     */
    public function getAppId(): string
    {
        return $this->app_id;
    }

    /**
     * get Secret.
     */
    public function getSecret(): string
    {
        return $this->secret;
    }
}
