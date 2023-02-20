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

class CreateThirdPartyRequestLog extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('third_party_request_log');
    }
}
