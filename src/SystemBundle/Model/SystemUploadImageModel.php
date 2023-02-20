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
 * @property int $img_id
 * @property string $img_storage
 * @property string $img_name
 * @property string $img_type
 * @property string $img_url
 * @property string $img_brief
 * @property int $img_size
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemUploadImageModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_upload_images';

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'img_id';

    /**
     * The table all columns.
     */
    protected array $cols = ['img_id', 'img_storage', 'img_name', 'img_type', 'img_url', 'img_brief', 'img_size', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['img_id' => 'integer', 'img_size' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
