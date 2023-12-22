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
 * @property string $file_type
 * @property int $file_rel_id
 * @property string $file_name
 * @property int $file_size
 * @property string $handle_raw_path
 * @property string $handle_error_path
 * @property string $handle_status
 * @property int $handle_line_num
 * @property int $success_line_num
 * @property int $error_line_num
 * @property string $finish_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemUploadFileModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_upload_file';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    /**
     * The table all columns.
     */
    protected array $cols = ['id', 'user_id', 'file_type', 'file_rel_id', 'file_name', 'file_size', 'handle_raw_path', 'handle_error_path', 'handle_status', 'handle_line_num', 'success_line_num', 'error_line_num', 'finish_time', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'file_rel_id' => 'integer', 'file_size' => 'integer', 'handle_line_num' => 'integer', 'success_line_num' => 'integer', 'error_line_num' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
