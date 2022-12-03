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
 * @property int $log_id
 * @property int $user_id
 * @property string $from_ip
 * @property string $request_uri
 * @property string $request_method
 * @property string $api_alias
 * @property string $api_name
 * @property string $params
 * @property \Carbon\Carbon $created_at
 */
class SystemOperationLogModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_operation_log';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'log_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['log_id', 'user_id', 'from_ip', 'request_uri', 'request_method', 'api_alias', 'api_name', 'params', 'created_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['log_id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime'];
}
