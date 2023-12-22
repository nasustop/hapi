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

            $table->index('user_id', 'idx_user_id');
            $table->index('request_uri', 'idx_request_uri');
            $table->index('request_method', 'idx_request_method');
            $table->index('api_alias', 'idx_api_alias');
            $table->index('api_name', 'idx_api_name');
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

            $table->index('user_id', 'idx_user_id');
            $table->index('file_type', 'idx_file_type');
            $table->index('file_rel_id', 'idx_file_rel_id');
            $table->index('handle_status', 'idx_handle_status');
        });
        Schema::create('system_upload_file_message', function (Blueprint $table) {
            $table->unsignedBigInteger('upload_id')->comment('关联ID');
            $table->unsignedInteger('line_num')->comment('处理文件的行数');
            $table->json('raw_data')->comment('原始数据');
            $table->enum('handle_status', ['wait', 'success', 'error'])->default('wait')->comment('处理状态');
            $table->string('error_message')->default('')->comment('错误原因');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->index('upload_id', 'idx_upload_id');
            $table->index('line_num', 'idx_line_num');
            $table->index('handle_status', 'idx_handle_status');
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

            $table->index('img_storage', 'idx_img_storage');
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

            $table->index('user_id', 'idx_user_id');
            $table->index('export_type', 'idx_export_type');
            $table->index('handle_status', 'idx_handle_status');
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

            $table->index('method', 'idx_method');
            $table->index('host', 'idx_host');
            $table->index('path', 'idx_path');
            $table->index('status_code', 'idx_status_code');
            $table->index('status', 'idx_status');
            $table->index('transfer_time', 'idx_transfer_time');
            $table->index('created_at', 'idx_created');
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
