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

class CreateSystemUploadImages extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_upload_images');
    }
}
