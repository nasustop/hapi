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
use SystemBundle\Model\SystemPowerModel;

class SystemPowerRepository extends Repository
{
    public const ENUM_PARENT_TYPE_USER = 'user';

    public const ENUM_PARENT_TYPE_ROLE = 'role';

    public const ENUM_PARENT_TYPE = [self::ENUM_PARENT_TYPE_USER => 'user', self::ENUM_PARENT_TYPE_ROLE => 'role'];

    public const ENUM_PARENT_TYPE_DEFAULT = self::ENUM_PARENT_TYPE_USER;

    public const ENUM_CHILDREN_TYPE_ROLE = 'role';

    public const ENUM_CHILDREN_TYPE_MENU = 'menu';

    public const ENUM_CHILDREN_TYPE = [self::ENUM_CHILDREN_TYPE_ROLE => 'role', self::ENUM_CHILDREN_TYPE_MENU => 'menu'];

    public const ENUM_CHILDREN_TYPE_DEFAULT = self::ENUM_CHILDREN_TYPE_ROLE;

    protected SystemPowerModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumParentType(): array
    {
        return self::ENUM_PARENT_TYPE;
    }

    public function enumParentTypeDefault(): string
    {
        return self::ENUM_PARENT_TYPE_DEFAULT;
    }

    public function enumChildrenType(): array
    {
        return self::ENUM_CHILDREN_TYPE;
    }

    public function enumChildrenTypeDefault(): string
    {
        return self::ENUM_CHILDREN_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemPowerModel
    {
        if (empty($this->model)) {
            $this->model = container()->get(SystemPowerModel::class);
        }
        return $this->model;
    }
}
