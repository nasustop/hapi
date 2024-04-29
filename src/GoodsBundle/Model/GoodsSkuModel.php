<?php

declare (strict_types=1);
namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;
/**
 * @property int $sku_id 
 * @property int $spu_id 
 * @property string $status 
 * @property int $is_default 
 * @property string $sku_code 
 * @property int $sale_price 
 * @property int $market_price 
 * @property int $const_price 
 * @property int $stock_num 
 * @property int $sold_num 
 * @property string $sku_weight 
 * @property string $sku_volume 
 * @property int $open_images 
 * @property string $sku_images 
 * @property int $open_params 
 * @property int $open_intro 
 * @property string $sku_intro 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class GoodsSkuModel extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods_sku';
    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'sku_id';
    /**
     * The table all columns.
     */
    protected array $cols = ['sku_id', 'spu_id', 'status', 'is_default', 'sku_code', 'sale_price', 'market_price', 'const_price', 'stock_num', 'sold_num', 'sku_weight', 'sku_volume', 'open_images', 'sku_images', 'open_params', 'open_intro', 'sku_intro', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];
    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['sku_id' => 'integer', 'spu_id' => 'integer', 'is_default' => 'integer', 'sale_price' => 'integer', 'market_price' => 'integer', 'const_price' => 'integer', 'stock_num' => 'integer', 'sold_num' => 'integer', 'open_images' => 'integer', 'open_params' => 'integer', 'open_intro' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}