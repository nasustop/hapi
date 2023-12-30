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

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsSpecModel;

class GoodsSpecRepository extends Repository
{
    public const ENUM_SHOW_TYPE_TEXT = 'text';

    public const ENUM_SHOW_TYPE_IMG = 'img';

    public const ENUM_SHOW_TYPE_ALL = 'all';

    public const ENUM_SHOW_TYPE = [self::ENUM_SHOW_TYPE_TEXT => 'text', self::ENUM_SHOW_TYPE_IMG => 'img', self::ENUM_SHOW_TYPE_ALL => 'all'];

    public const ENUM_SHOW_TYPE_DEFAULT = self::ENUM_SHOW_TYPE_TEXT;

    protected GoodsSpecModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumShowType(): array
    {
        return self::ENUM_SHOW_TYPE;
    }

    public function enumShowTypeDefault(): string
    {
        return self::ENUM_SHOW_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSpecModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSpecModel::class);
        }
        return $this->model;
    }
}
