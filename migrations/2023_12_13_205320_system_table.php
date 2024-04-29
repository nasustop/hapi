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

class SystemTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_api', function (Blueprint $table) {
            $table->bigIncrements('api_id')->comment('接口ID');
            $table->string('api_name', 100)->comment('接口名称');
            $table->string('api_alias', 100)->comment('接口别名，全局唯一');
            $table->json('api_method')->comment('请求方式');
            $table->string('api_uri')->comment('接口地址');
            $table->string('api_action')->comment('接口方法');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->unique('api_alias');
            $table->comment('系统接口API表');
        });

        Schema::create('system_menu1', function (Blueprint $table) {
            $table->bigIncrements('menu_id')->comment('菜单ID');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->string('menu_name', 100)->comment('菜单名称');
            $table->string('menu_alias', 50)->comment('菜单别名，全局唯一');
            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
            $table->smallInteger('is_show')->default(1)->comment('是否显示');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('parent_id');
            $table->index('is_show');
            $table->unique('menu_alias');
            $table->comment('系统菜单表');
        });

//        Schema::create('system_menu', function (Blueprint $table) {
//            $table->bigIncrements('menu_id')->comment('菜单ID');
//            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
//            $table->enum('menu_type', ['menu', 'page'])->comment('菜单类型');
//            $table->string('menu_name')->comment('菜单名称');
//            $table->string('menu_alias', 100)->comment('菜单别名，全局唯一');
//            $table->string('menu_icon')->comment('菜单icon');
//            $table->boolean('open_component')->comment('是否开启自定义页面地址');
//            $table->string('component_path')->nullable()->comment('自定义页面地址');
//            $table->boolean('open_redirect')->comment('是否开启重定向');
//            $table->string('redirect_path')->nullable()->comment('重定向地址');
//            $table->boolean('menu_hidden')->default(false)->comment('是否隐藏');
//            $table->string('activity_menu')->nullable()->comment('activityMenu');
//            $table->unsignedInteger('sort', false)->default(0)->comment('排序');
//            $table->timestamp('created_at')->nullable()->comment('添加时间');
//            $table->timestamp('updated_at')->nullable()->comment('修改时间');
//
//            $table->index('parent_id');
//            $table->index('menu_type');
//            $table->unique('menu_alias');
//            $table->comment('系统菜单表');
//        });

        Schema::create('system_role', function (Blueprint $table) {
            $table->bigIncrements('role_id')->comment('角色ID');
            $table->string('role_name', 100)->comment('角色名称');
            $table->string('role_alias', 50)->comment('角色别名，全局唯一');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->unique('role_alias');
            $table->comment('系统角色表');
        });
        Schema::create('system_power', function (Blueprint $table) {
            $table->enum('parent_type', ['user', 'role', 'menu'])->comment('父节点类型');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->enum('children_type', ['role', 'menu', 'api'])->comment('子节点类型');
            $table->unsignedBigInteger('children_id', false)->comment('父节点ID');

            $table->unique(['parent_type', 'parent_id', 'children_type', 'children_id'], 'unique_power');
            $table->index('parent_type');
            $table->index('parent_id');
            $table->index('children_type');
            $table->comment('系统权限表');
        });
        Schema::create('system_user', function (Blueprint $table) {
            $table->bigIncrements('user_id')->comment('用户ID');
            $table->string('password')->comment('密码');
            $table->string('password_hash')->comment('密码密钥');
            $table->string('user_name', 100)->comment('用户昵称');
            $table->string('avatar_url')->nullable()->comment('用户头像');
            $table->enum('user_status', ['success', 'disabled'])->default('success')->comment('用户状态 success:正常 disabled:禁用');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->index('user_status');
            $table->comment('系统用户表');
        });
        Schema::create('system_user_rel_account', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->enum('rel_type', ['account', 'email', 'mobile', 'mini_app', 'official_account'])->comment('关联类型');
            $table->string('rel_value', 100)->comment('关联索引');
            $table->json('rel_data')->nullable()->comment('冗余数据');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->index('user_id');
            $table->index('rel_type');
            $table->unique(['rel_value', 'deleted_at'], 'unique_rel_value');
            $table->comment('系统用户账号表');
        });
        Schema::create('system_operation_log', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->ipAddress('from_ip');
            $table->string('request_uri');
            $table->string('request_method');
            $table->string('api_alias');
            $table->string('api_name');
            $table->json('params');
            $table->timestamp('created_at')->nullable()->comment('添加时间');

            $table->index('user_id');
            $table->index('request_uri');
            $table->index('request_method');
            $table->index('api_alias');
            $table->index('api_name');
            $table->comment('系统操作记录表');
        });
        Schema::create('system_upload_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('操作用户');
            $table->string('file_type')->comment('上传文件类型');
            $table->unsignedBigInteger('file_rel_id')->comment('上传文件关联ID');
            $table->string('file_name')->comment('上传文件名称');
            $table->unsignedInteger('file_size')->comment('上传文件大小');
            $table->string('handle_raw_path')->comment('原始文件地址');
            $table->string('handle_error_path')->default('')->comment('错误信息文件地址');
            $table->enum('handle_status', ['wait', 'processing', 'finish'])->default('wait')->comment('处理文件状态');
            $table->unsignedInteger('handle_line_num')->default(0)->comment('处理文件行数');
            $table->unsignedInteger('success_line_num')->default(0)->comment('处理成功行数');
            $table->unsignedInteger('error_line_num')->default(0)->comment('处理失败行数');
            $table->timestamp('finish_time')->comment('处理完成时间');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('user_id');
            $table->index('file_type');
            $table->index('file_rel_id');
            $table->index('handle_status');
            $table->comment('系统文件上传记录表');
        });
        Schema::create('system_upload_file_message', function (Blueprint $table) {
            $table->unsignedBigInteger('upload_id')->comment('关联ID');
            $table->unsignedInteger('line_num')->comment('处理文件的行数');
            $table->json('raw_data')->comment('原始数据');
            $table->enum('handle_status', ['wait', 'success', 'error'])->default('wait')->comment('处理状态');
            $table->string('error_message')->default('')->comment('错误原因');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('upload_id');
            $table->index('line_num');
            $table->index('handle_status');
            $table->primary('upload_id');
            $table->comment('系统文件上传错误信息记录表');
        });
        Schema::create('system_upload_images', function (Blueprint $table) {
            $table->bigIncrements('img_id')->comment('图片ID');
            $table->enum('img_storage', ['local', 'ftp', 'memory', 's3', 'minio', 'oss', 'qiniu', 'cos'])->comment('存储器');
            $table->string('img_name')->comment('图片名称');
            $table->string('img_type')->comment('图片类型');
            $table->string('img_url')->comment('图片地址');
            $table->string('img_brief')->comment('图片简介');
            $table->integer('img_size')->default(0)->comment('图片大小');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('img_storage');
            $table->comment('系统图片素材管理表');
        });
        Schema::create('system_export_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('操作用户');
            $table->string('export_type')->comment('导出文件类型');
            $table->json('request_data')->comment('请求数据');
            $table->enum('handle_status', ['wait', 'processing', 'finish', 'fail'])->default('wait')->comment('处理文件状态');
            $table->timestamp('finish_time')->nullable()->comment('处理完成时间');
            $table->string('file_name')->default('')->comment('导出文件名称');
            $table->string('file_url')->default('')->comment('导出文件路径');
            $table->string('error_message')->default('')->comment('错误原因');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('user_id');
            $table->index('export_type');
            $table->index('handle_status');
            $table->comment('系统文件导出记录表');
        });
        Schema::create('third_party_request_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('method', ['GET', 'POST', 'PUT', 'DELETE', 'HEAD'])->comment('请求方式');
            $table->string('host')->comment('域名');
            $table->string('path')->comment('地址');
            $table->json('params')->comment('参数');
            $table->unsignedInteger('status_code')->comment('返回的http状态');
            $table->enum('status', ['success', 'fail'])->default('success')->comment('接口状态');
            $table->float('transfer_time', 8, 3)->comment('请求时长');
            $table->string('result')->comment('返回结果');
            $table->timestamp('created_at')->nullable()->comment('日志记录时间');

            $table->index('method');
            $table->index('host');
            $table->index('path');
            $table->index('status_code');
            $table->index('status');
            $table->index('transfer_time');
            $table->index('created_at');
            $table->comment('第三方接口日志表');
        });
        Schema::create('system_wechat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('driver', ['official_account', 'mini_app'])->comment('driver');
            $table->string('app_id')->comment('app_id');
            $table->string('secret')->comment('secret');
            $table->string('token')->default('')->comment('token');
            $table->string('aes_key')->default('')->comment('aes key');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');

            $table->unique(['app_id', 'deleted_at'], 'unique_app_id');
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
        Schema::dropIfExists('system_user_rel_account');
        Schema::dropIfExists('system_operation_log');
        Schema::dropIfExists('system_upload_file');
        Schema::dropIfExists('system_upload_file_message');
        Schema::dropIfExists('system_upload_images');
        Schema::dropIfExists('system_export_file');
        Schema::dropIfExists('third_party_request_log');
        Schema::dropIfExists('system_wechat');
    }
}
