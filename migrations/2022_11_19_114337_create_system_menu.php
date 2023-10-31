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

class CreateSystemMenu extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_menu', function (Blueprint $table) {
            $table->bigIncrements('menu_id')->comment('菜单ID');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->string('menu_name', 100)->comment('菜单名称');
            $table->string('menu_alias', 50)->comment('菜单别名，全局唯一');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->smallInteger('is_show')->default(1)->comment('是否显示');
            $table->enum('menu_type', ['menu', 'page', 'apis'])->default('menu')->comment('类型：menu菜单 page页面 apis接口权限');
            $table->json('apis')->nullable(true)->comment('权限集合');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->unique('menu_alias', 'unique_menu_alias');
            $table->index('is_show', 'index_is_show');
            $table->index('menu_type', 'index_menu_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_menu');
    }
}
