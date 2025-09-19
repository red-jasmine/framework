<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Organization\Domain\Models\Enums\DepartmentStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('org_id')->default(0)->comment('组织ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级部门ID');
            $table->string('name')->comment('部门名称');
            $table->string('short_name')->nullable()->comment('部门简称');
            $table->string('code')->nullable()->comment('部门编码');
            $table->unsignedInteger('sort')->default(0)->comment('同级排序');
            $table->enum('status', DepartmentStatusEnum::values())->default(DepartmentStatusEnum::ENABLE->value)->comment(DepartmentStatusEnum::comments('状态'));
            $table->operator();

            // 索引定义
            $table->index('org_id', 'idx_org_id');
            $table->index('parent_id', 'idx_parent_id');
            $table->comment('部门表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('departments');
    }
};


