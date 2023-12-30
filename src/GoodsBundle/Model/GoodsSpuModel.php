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
 * @property string $spu_name
 * @property string $spu_brief
 * @property string $spu_thumb
 * @property string $spu_images
 * @property string $spu_intro
 * @property string $status
 * @property string $spu_type
 * @property int $price_min
 * @property int $price_max
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class GoodsSpuModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_spu';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'spu_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['spu_id', 'spu_name', 'spu_brief', 'spu_thumb', 'spu_images', 'status', 'spu_type', 'price_min', 'price_max', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['spu_id' => 'integer', 'price_min' => 'integer', 'price_max' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
