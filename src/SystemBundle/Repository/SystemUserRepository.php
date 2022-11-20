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
namespace SystemBundle\Repository;

use App\Repository\Repository;
use Hyperf\Di\Annotation\Inject;
use SystemBundle\Model\SystemUserModel;

class SystemUserRepository extends Repository
{
    #[Inject]
    protected SystemUserModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['user_id', 'user_name', 'avatar_url', 'login_name', 'password', 'mobile', 'user_status', 'created_at', 'updated_at'];

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemUserModel
    {
        return $this->model;
    }
}
