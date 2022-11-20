<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateSystemUser extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_user', function (Blueprint $table) {
            $table->bigIncrements('user_id')->comment('用户ID');
            $table->string('user_name', 100)->comment('用户昵称');
            $table->string('avatar_url')->nullable()->comment('用户头像');
            $table->string('login_name', 32)->comment('登录帐号');
            $table->string('password')->comment('密码');
            $table->string('mobile', 30)->comment('手机号');
            $table->string('user_status', 30)->default('success')->comment('用户状态 success:正常 disabled:禁用');
            $table->timestamps();
            $table->time('deleted_at')->nullable()->comment('删除时间');

            $table->unique(['login_name', 'deleted_at'], 'idx_login_name');
            $table->unique(['mobile', 'deleted_at'], 'idx_mobile');
            $table->index('user_status', 'idx_user_status');
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
