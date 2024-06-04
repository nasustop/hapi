<?php

declare (strict_types=1);
namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;
/**
 * @property int $spu_id 
 * @property int $spec_id 
 * @property int $is_custom_spec 
 * @property string $spec_name 
 * @property int $spec_sort 
 * @property int $spec_value_id 
 * @property int $is_custom_spec_value 
 * @property int $is_default_spec_value 
 * @property string $spec_value_name 
 * @property string $spec_value_img 
 * @property int $spec_value_sort 
 */
class GoodsSpuRelSpecModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_spu_rel_spec';
    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'spec_value_id';
    /**
     * The table all columns.
     */
    protected array $cols = ['spu_id', 'spec_id', 'is_custom_spec', 'spec_name', 'spec_sort', 'spec_value_id', 'is_custom_spec_value', 'is_default_spec_value', 'spec_value_name', 'spec_value_img', 'spec_value_sort'];
    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['spu_id' => 'integer', 'spec_id' => 'integer', 'is_custom_spec' => 'integer', 'spec_sort' => 'integer', 'spec_value_id' => 'integer', 'is_custom_spec_value' => 'integer', 'is_default_spec_value' => 'integer', 'spec_value_sort' => 'integer'];
}