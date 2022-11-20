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
use SystemBundle\Model\SystemPowerModel;

class SystemPowerRepository extends Repository
{
    #[Inject]
    protected SystemPowerModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'parent_type', 'parent_id', 'children_type', 'children_id'];

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemPowerModel
    {
        return $this->model;
    }
}
