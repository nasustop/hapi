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

class CreateSystemPower extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_power', function (Blueprint $table) {
            $table->enum('parent_type', ['user', 'role'])->comment('父节点类型');
            $table->unsignedBigInteger('parent_id', false)->comment('父节点ID');
            $table->enum('children_type', ['role', 'menu'])->comment('子节点类型');
            $table->unsignedBigInteger('children_id', false)->comment('父节点ID');

            $table->unique(['parent_type', 'parent_id', 'children_type', 'children_id'], 'unique_power');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_power');
    }
}
