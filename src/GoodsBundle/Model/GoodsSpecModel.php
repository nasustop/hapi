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
 * @property int $spec_id
 * @property string $spec_name
 * @property int $sort
 * @property string $show_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class GoodsSpecModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_spec';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'spec_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['spec_id', 'spec_name', 'sort', 'show_type', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['spec_id' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
