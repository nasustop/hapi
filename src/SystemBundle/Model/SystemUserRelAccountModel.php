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
 * @property int $id
 * @property int $user_id
 * @property string $rel_type
 * @property string $rel_value
 * @property string $rel_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemUserRelAccountModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_user_rel_account';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'user_id', 'rel_type', 'rel_value', 'rel_data', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
