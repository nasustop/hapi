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
 * @property int $category_id
 * @property int $parent_id
 * @property string $category_name
 * @property string $category_img
 * @property int $sort
 * @property int $is_show
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class GoodsCategoryModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_category';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'category_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['category_id', 'parent_id', 'category_name', 'category_img', 'sort', 'is_show', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['category_id' => 'integer', 'parent_id' => 'integer', 'sort' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
