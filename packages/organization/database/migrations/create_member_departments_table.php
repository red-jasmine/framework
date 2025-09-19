<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('member_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('member_id')->comment('成员ID');
            $table->unsignedBigInteger('department_id')->comment('部门ID');
            $table->boolean('is_primary')->default(false)->comment('是否主部门');
             $table->operator();

            // 索引定义
            $table->index('member_id', 'idx_member_id');
            $table->index('department_id', 'idx_department_id');
            $table->index('is_primary', 'idx_is_primary');
            $table->unique(['member_id', 'department_id'], 'uk_member_dept');
            $table->comment('成员-部门关系表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('member_departments');
    }
};


