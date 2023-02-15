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

class CreateSystemWechat extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
        Schema::dropIfExists('system_wechat');
    }
}
