<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('member_positions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('member_id')->comment('成员ID');
            $table->unsignedBigInteger('position_id')->comment('职位ID');
             $table->operator();

            // 索引定义
            $table->index('member_id', 'idx_member_id');
            $table->index('position_id', 'idx_position_id');
            $table->unique(['member_id', 'position_id'], 'uk_member_position');
            $table->comment('成员-职位关系表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('member_positions');
    }
};


