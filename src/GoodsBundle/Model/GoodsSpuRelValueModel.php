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

namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;

/**
 * @property int $spu_id
 * @property string $rel_type
 * @property int $rel_id
 */
class GoodsSpuRelValueModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_spu_rel_value';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = '';

    /**
     * The table all columns.
     */
    protected array $cols = ['spu_id', 'rel_type', 'rel_id'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['spu_id' => 'integer', 'rel_id' => 'integer'];
}
