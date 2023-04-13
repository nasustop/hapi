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

class CreateSystemUploadFile extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_upload_file');
    }
}
