<?php

declare (strict_types=1);
namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;
/**
 * @property int $category_id 
 * @property string $rel_type_type 
 * @property int $rel_id 
 */
class GoodsCategoryRelValueModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_category_rel_value';
    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'category_id';
    /**
     * The table all columns.
     */
    protected array $cols = ['category_id', 'rel_type_type', 'rel_id'];
    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['category_id' => 'integer', 'rel_id' => 'integer'];
}