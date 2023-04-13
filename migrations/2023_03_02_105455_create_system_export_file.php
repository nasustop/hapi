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

class CreateSystemExportFile extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_export_file');
    }
}
