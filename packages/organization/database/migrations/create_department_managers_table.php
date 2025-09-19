<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('department_managers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('department_id')->comment('部门ID');
            $table->unsignedBigInteger('member_id')->comment('成员ID');
            $table->boolean('is_primary')->default(false)->comment('是否主要负责人');
            $table->operator();

            // 索引定义
            $table->index('department_id', 'idx_department_id');
            $table->index('member_id', 'idx_member_id');
            $table->index('is_primary', 'idx_is_primary');
            $table->unique(['department_id', 'member_id'], 'uk_dept_member');
            $table->comment('部门管理者表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('department_managers');
    }
};


