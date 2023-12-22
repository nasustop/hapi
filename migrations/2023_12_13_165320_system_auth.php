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

class SystemAuth extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_api', function (Blueprint $table) {
            $table->bigIncrements('api_id')->comment('接口ID');
            $table->string('api_name', 100)->comment('接口名称');
            $table->string('api_alias', 100)->comment('接口别名，全局唯一')->unique();
            $table->json('api_method')->comment('请求方式');
            $table->string('api_uri')->comment('接口地址');
            $table->string('api_action')->comment('接口方法');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->unique('api_alias', 'unique_api_alias');
        });

        Schema::create('system_menu', function (Blueprint $table) {
            $table->bigIncrements('menu_id')->comment('菜单ID');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->string('menu_name', 100)->comment('菜单名称');
            $table->string('menu_alias', 50)->comment('菜单别名，全局唯一');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->smallInteger('is_show')->default(1)->comment('是否显示');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('parent_id', 'index_parent_id');
            $table->index('is_show', 'index_is_show');
            $table->unique('menu_alias', 'unique_menu_alias');
        });

        Schema::create('system_role', function (Blueprint $table) {
            $table->bigIncrements('role_id')->comment('角色ID');
            $table->string('role_name', 100)->comment('角色名称');
            $table->string('role_alias', 50)->comment('角色别名，全局唯一');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->unique('role_alias', 'unique_role_alias');
        });
        Schema::create('system_power', function (Blueprint $table) {
            $table->enum('parent_type', ['user', 'role', 'menu'])->comment('父节点类型');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->enum('children_type', ['role', 'menu', 'api'])->comment('子节点类型');
            $table->unsignedBigInteger('children_id', false)->comment('父节点ID');

            $table->unique(['parent_type', 'parent_id', 'children_type', 'children_id'], 'unique_power');
        });
        Schema::create('system_user', function (Blueprint $table) {
            $table->bigIncrements('user_id')->comment('用户ID');
            $table->string('password')->comment('密码');
            $table->string('password_hash')->comment('密码密钥');
            $table->string('user_name', 100)->comment('用户昵称');
            $table->string('avatar_url')->nullable()->comment('用户头像');
            $table->enum('user_status', ['success', 'disabled'])->default('success')->index()->comment('用户状态 success:正常 disabled:禁用');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
        });
        Schema::create('system_user_rel_account', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->enum('rel_type', ['account', 'email', 'mobile', 'mini_app', 'official_account'])->index()->comment('关联类型');
            $table->string('rel_value', 100)->comment('关联索引');
            $table->json('rel_data')->nullable()->comment('冗余数据');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->index('user_id', 'index_user_id');
            $table->unique(['rel_value', 'deleted_at'], 'unique_rel_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_api');
        Schema::dropIfExists('system_menu');
        Schema::dropIfExists('system_role');
        Schema::dropIfExists('system_power');
        Schema::dropIfExists('system_user');
        Schema::dropIfExists('system_user');
    }
}
