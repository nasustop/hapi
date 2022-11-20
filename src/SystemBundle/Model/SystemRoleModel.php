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
 * @property int $role_id
 * @property string $role_name
 * @property string $role_alias
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemRoleModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_user';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'role_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['role_id', 'role_name', 'role_alias', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'role_id' => 'integer'];
}
