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
use Hyperf\Di\Annotation\Inject;
use SystemBundle\Model\SystemRoleModel;

class SystemRoleRepository extends Repository
{
    #[Inject]
    protected SystemRoleModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['role_id', 'role_name', 'role_alias', 'created_at', 'updated_at'];

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemRoleModel
    {
        return $this->model;
    }
}
