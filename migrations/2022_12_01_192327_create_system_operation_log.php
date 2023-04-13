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

class CreateSystemOperationLog extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_operation_log');
    }
}
