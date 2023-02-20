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
 * @property int $user_id
 * @property string $user_name
 * @property string $avatar_url
 * @property string $login_name
 * @property string $password
 * @property string $mobile
 * @property string $user_status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class SystemUserModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_user';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'user_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['user_id', 'user_name', 'avatar_url', 'login_name', 'password', 'mobile', 'user_status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
