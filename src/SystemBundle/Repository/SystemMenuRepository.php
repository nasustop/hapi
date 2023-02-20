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
use SystemBundle\Model\SystemMenuModel;

class SystemMenuRepository extends Repository
{
    public const ENUM_MENU_TYPE_MENU = 'menu';

    public const ENUM_MENU_TYPE_APIS = 'apis';

    public const ENUM_MENU_TYPE = [self::ENUM_MENU_TYPE_MENU => 'menu', self::ENUM_MENU_TYPE_APIS => 'apis'];

    public const ENUM_MENU_TYPE_DEFAULT = self::ENUM_MENU_TYPE_MENU;

    #[Inject]
    protected SystemMenuModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumMenuType(): array
    {
        return self::ENUM_MENU_TYPE;
    }

    public function enumMenuTypeDefault(): string
    {
        return self::ENUM_MENU_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemMenuModel
    {
        return $this->model;
    }
}
