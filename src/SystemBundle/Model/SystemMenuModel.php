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

namespace SystemBundle\Model;

use App\Model\Model as BaseModel;

/**
 * @property int $menu_id
 * @property int $parent_id
 * @property string $menu_name
 * @property string $menu_alias
 * @property int $sort
 * @property int $is_show
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemMenuModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_menu';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'menu_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['menu_id', 'parent_id', 'menu_name', 'menu_alias', 'sort', 'is_show', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['menu_id' => 'integer', 'parent_id' => 'integer', 'sort' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
