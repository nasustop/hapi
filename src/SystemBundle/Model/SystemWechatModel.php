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
 * @property string $driver
 * @property string $app_id
 * @property string $secret
 * @property string $token
 * @property string $aes_key
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class SystemWechatModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_wechat';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'driver', 'app_id', 'secret', 'token', 'aes_key', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
