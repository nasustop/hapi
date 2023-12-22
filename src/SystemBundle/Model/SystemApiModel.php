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
 * @property int $api_id
 * @property string $api_name
 * @property string $api_alias
 * @property string $api_method
 * @property string $api_uri
 * @property string $api_action
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemApiModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_api';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'api_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['api_id', 'api_name', 'api_alias', 'api_method', 'api_uri', 'api_action', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['api_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
