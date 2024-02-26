<?php

declare (strict_types=1);
namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;
/**
 * @property int $params_id 
 * @property string $params_name 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class GoodsParamModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_params';
    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'params_id';
    /**
     * The table all columns.
     */
    protected array $cols = ['params_id', 'params_name', 'sort', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['params_id' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}