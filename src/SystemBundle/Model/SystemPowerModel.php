<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
namespace SystemBundle\Model;

use App\Model\Model as BaseModel;

/**
 * @property int $id
 * @property string $parent_type
 * @property int $parent_id
 * @property string $children_type
 * @property int $children_id
 */
class SystemPowerModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_user';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'parent_type', 'parent_id', 'children_type', 'children_id'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'id' => 'integer', 'parent_id' => 'integer', 'children_id' => 'integer'];
}
