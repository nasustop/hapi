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

class CreateSystemUserRelAccount extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_user_rel_account', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->enum('rel_type', ['account', 'email', 'mobile', 'mini_app', 'official_account'])->comment('关联类型');
            $table->string('rel_key', 100)->comment('关联索引');
            $table->json('rel_value')->nullable()->comment('冗余数据');
            $table->timestamp('created_at')->nullable()->comment('添加时间');
            $table->timestamp('updated_at')->nullable()->comment('修改时间');

            $table->unique('rel_key', 'unique_rel_key');
            $table->index('user_id', 'index_user');
            $table->index('rel_type', 'index_rel_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_user');
    }
}
