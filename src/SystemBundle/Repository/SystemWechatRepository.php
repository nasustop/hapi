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
namespace SystemBundle\Repository;

use App\Repository\Repository;
use SystemBundle\Model\SystemWechatModel;

class SystemWechatRepository extends Repository
{
    public const ENUM_DRIVER_OFFICIAL_ACCOUNT = 'official_account';

    public const ENUM_DRIVER_MINI_APP = 'mini_app';

    public const ENUM_DRIVER = [self::ENUM_DRIVER_OFFICIAL_ACCOUNT => '公众号', self::ENUM_DRIVER_MINI_APP => '小程序'];

    public const ENUM_DRIVER_DEFAULT = self::ENUM_DRIVER_OFFICIAL_ACCOUNT;

    protected SystemWechatModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumDriver(): array
    {
        return self::ENUM_DRIVER;
    }

    public function enumDriverDefault(): string
    {
        return self::ENUM_DRIVER_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemWechatModel
    {
        if (empty($this->model)) {
            $this->model = make(SystemWechatModel::class);
        }
        return $this->model;
    }
}
