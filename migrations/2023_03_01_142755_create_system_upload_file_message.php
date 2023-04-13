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

class CreateSystemUploadFileMessage extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_upload_file_message');
    }
}
