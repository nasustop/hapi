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
use SystemBundle\Model\SystemMenuModel;

class SystemMenuRepository extends Repository
{
    #[Inject]
    protected SystemMenuModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['menu_id', 'parent_id', 'menu_name', 'menu_alias', 'sort', 'is_show', 'menu_type', 'apis', 'created_at', 'updated_at'];

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemMenuModel
    {
        return $this->model;
    }
}
