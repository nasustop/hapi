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
use SystemBundle\Model\SystemPowerModel;

class SystemPowerRepository extends Repository
{
    public const PARENT_TYPE_USER = 'user';

    public const PARENT_TYPE_ROLE = 'role';

    public const ENUM_PARENT_TYPE = [
        self::PARENT_TYPE_USER => '用户',
        self::PARENT_TYPE_ROLE => '角色',
    ];

    public const CHILDREN_TYPE_ROLE = 'role';

    public const CHILDREN_TYPE_MENU = 'menu';

    public const ENUM_CHILDREN_TYPE = [
        self::CHILDREN_TYPE_ROLE => '角色',
        self::CHILDREN_TYPE_MENU => '菜单',
    ];

    #[Inject]
    protected SystemPowerModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['parent_type', 'parent_id', 'children_type', 'children_id'];

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
