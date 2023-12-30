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

namespace GoodsBundle\Model;

use App\Model\Model as BaseModel;

/**
 * @property int $sku_id
 * @property int $spu_id
 * @property string $sku_thumb
 * @property int $sale_price
 * @property int $market_price
 * @property string $sku_code
 * @property int $stock_num
 * @property int $sold_num
 * @property int $sort
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
    protected array $cols = ['sku_id', 'spu_id', 'sku_thumb', 'sale_price', 'market_price', 'sku_code', 'stock_num', 'sold_num', 'sort', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['sku_id' => 'integer', 'spu_id' => 'integer', 'sale_price' => 'integer', 'market_price' => 'integer', 'stock_num' => 'integer', 'sold_num' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
