<?php

declare (strict_types=1);
namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;
/**
 * @property int $spu_id 
 * @property int $sku_id 
 * @property string $rel_type 
 * @property int $rel_id 
 */
class GoodsSkuRelValueModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_sku_rel_value';
    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'rel_id';
    /**
     * The table all columns.
     */
    protected array $cols = ['spu_id', 'sku_id', 'rel_type', 'rel_id'];
    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['spu_id' => 'integer', 'sku_id' => 'integer', 'rel_id' => 'integer'];
}