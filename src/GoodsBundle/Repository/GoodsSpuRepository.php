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
use GoodsBundle\Model\GoodsSpuModel;

class GoodsSpuRepository extends Repository
{
    public const ENUM_STATUS_ON_SALE = 'on_sale';

    public const ENUM_STATUS_OFF_SALE = 'off_sale';

    public const ENUM_STATUS = [self::ENUM_STATUS_ON_SALE => 'on_sale', self::ENUM_STATUS_OFF_SALE => 'off_sale'];

    public const ENUM_STATUS_DEFAULT = self::ENUM_STATUS_ON_SALE;

    public const ENUM_SPU_TYPE_NORMAL = 'normal';

    public const ENUM_SPU_TYPE_POINT = 'point';

    public const ENUM_SPU_TYPE_SERVICE = 'service';

    public const ENUM_SPU_TYPE = [self::ENUM_SPU_TYPE_NORMAL => 'normal', self::ENUM_SPU_TYPE_POINT => 'point', self::ENUM_SPU_TYPE_SERVICE => 'service'];

    public const ENUM_SPU_TYPE_DEFAULT = self::ENUM_SPU_TYPE_NORMAL;

    protected GoodsSpuModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumStatus(): array
    {
        return self::ENUM_STATUS;
    }

    public function enumStatusDefault(): string
    {
        return self::ENUM_STATUS_DEFAULT;
    }

    public function enumSpuType(): array
    {
        return self::ENUM_SPU_TYPE;
    }

    public function enumSpuTypeDefault(): string
    {
        return self::ENUM_SPU_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSpuModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSpuModel::class);
        }
        return $this->model;
    }
}
