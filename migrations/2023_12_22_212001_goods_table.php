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
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class GoodsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goods_category', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->string('category_name')->comment('分类名称');
            $table->string('category_img')->nullable()->comment('分类图片');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->smallInteger('is_show')->default(1)->comment('是否显示');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品分类表');
        });
        Schema::create('goods_brand', function (Blueprint $table) {
            $table->bigIncrements('brand_id');
            $table->string('brand_name')->comment('品牌名称');
            $table->string('brand_img')->nullable()->comment('品牌图片');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品品牌表');
        });
        Schema::create('goods_spec', function (Blueprint $table) {
            $table->bigIncrements('spec_id');
            $table->string('spec_name')->comment('规格名称');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->enum('show_type', ['text', 'img', 'all'])->comment('展示类型');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品规格表');
        });
        Schema::create('goods_spec_value', function (Blueprint $table) {
            $table->bigIncrements('spec_value_id');
            $table->unsignedBigInteger('spec_id', false)->comment('规格ID');
            $table->string('spec_value_name')->comment('属性名称');
            $table->string('spec_value_img')->nullable()->comment('属性图片');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品规格值表');
        });
        Schema::create('goods_params', function (Blueprint $table) {
            $table->bigIncrements('params_id');
            $table->string('params_name')->comment('参数名称');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品参数表');
        });
        Schema::create('goods_params_value', function (Blueprint $table) {
            $table->bigIncrements('params_value_id');
            $table->unsignedBigInteger('params_id', false)->comment('参数ID');
            $table->string('params_value_name')->comment('参数值');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品参数值表');
        });
        Schema::create('goods_spu', function (Blueprint $table) {
            $table->bigIncrements('spu_id');
            $table->string('spu_name')->comment('商品名称');
            $table->string('spu_brief')->comment('商品描述');
            $table->string('spu_thumb')->comment('商品缩略图');
            $table->json('spu_images')->comment('商品轮播图');
            $table->string('spu_video')->default('')->comment('商品视频');
            $table->longText('spu_intro')->nullable()->comment('商品详情');
            $table->enum('status', ['on_sale', 'off_sale'])->default('on_sale')->comment('商品状态');
            $table->enum('spu_type', ['normal', 'point', 'service'])->default('normal')->comment('商品类型');
            $table->unsignedBigInteger('price_min')->default(0)->comment('最低价');
            $table->unsignedBigInteger('price_max')->default(0)->comment('最高价');
            $table->unsignedBigInteger('sort')->default(0)->comment('商品排序');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品spu表');
        });
        Schema::create('goods_spu_rel_value', function (Blueprint $table) {
            $table->unsignedBigInteger('spu_id')->comment('spu id');
            $table->enum('rel_type', ['brand', 'category', 'params'])->comment('关联类型');
            $table->unsignedBigInteger('rel_id')->comment('关联id');

            $table->comment('商品参数值表');
        });
        Schema::create('goods_sku', function (Blueprint $table) {
            $table->bigIncrements('sku_id');
            $table->unsignedBigInteger('spu_id', false)->comment('商品SPU_ID');
            $table->enum('status', ['on_sale', 'off_sale'])->comment('商品状态');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认');
            $table->unsignedBigInteger('sale_price')->comment('售卖价格');
            $table->unsignedBigInteger('market_price')->comment('市场价格');
            $table->unsignedBigInteger('const_price')->comment('成本价格');
            $table->string('sku_code')->comment('商品编码');
            $table->unsignedBigInteger('stock_num')->default(0)->comment('库存');
            $table->unsignedBigInteger('sold_num')->default(0)->comment('已售数量');
            $table->float('sku_weight', 8, 3)->default(0)->comment('商品重量');
            $table->float('sku_volume', 8, 3)->default(0)->comment('商品体积');
            $table->unsignedBigInteger('sort', false)->default(0)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->comment('商品sku表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_category');
        Schema::dropIfExists('goods_brand');
        Schema::dropIfExists('goods_spec');
        Schema::dropIfExists('goods_spec_value');
        Schema::dropIfExists('goods_params');
        Schema::dropIfExists('goods_params_value');
        Schema::dropIfExists('goods_spu');
        Schema::dropIfExists('goods_spu_rel_value');
        Schema::dropIfExists('goods_sku');
    }
}
