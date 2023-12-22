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
 * @property int $upload_id
 * @property int $line_num
 * @property string $raw_data
 * @property string $handle_status
 * @property string $error_message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemUploadFileMessageModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_upload_file_message';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = '';

    /**
     * The table all columns.
     */
    protected array $cols = ['upload_id', 'line_num', 'raw_data', 'handle_status', 'error_message', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['upload_id' => 'integer', 'line_num' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
