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
 * @property string $export_type
 * @property string $request_data
 * @property string $handle_status
 * @property string $finish_time
 * @property string $file_name
 * @property string $file_url
 * @property string $error_message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemExportFileModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_export_file';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'user_id', 'export_type', 'request_data', 'handle_status', 'finish_time', 'file_name', 'file_url', 'error_message', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
