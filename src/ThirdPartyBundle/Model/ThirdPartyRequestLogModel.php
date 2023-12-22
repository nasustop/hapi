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

namespace ThirdPartyBundle\Model;

use App\Model\Model as BaseModel;

/**
 * @property int $id
 * @property string $method
 * @property string $host
 * @property string $path
 * @property string $params
 * @property int $status_code
 * @property string $status
 * @property string $transfer_time
 * @property string $result
 * @property \Carbon\Carbon $created_at
 */
class ThirdPartyRequestLogModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'third_party_request_log';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'method', 'host', 'path', 'params', 'status_code', 'status', 'transfer_time', 'result', 'created_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'status_code' => 'integer', 'created_at' => 'datetime'];
}
