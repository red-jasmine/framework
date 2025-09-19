<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Organization\Domain\Models\Enums\OrganizationStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级组织ID');
            $table->string('name')->comment('组织名称');
            $table->string('short_name')->nullable()->comment('组织简称');
            $table->string('code')->nullable()->comment('组织编码');
            $table->unsignedInteger('sort')->default(0)->comment('同级排序');
            $table->enum('status', OrganizationStatusEnum::values())->default(OrganizationStatusEnum::ENABLE->value)->comment(OrganizationStatusEnum::comments('状态'));
            $table->operator();


            // 索引定义
            $table->index('parent_id', 'idx_parent_id');
            $table->unique('code', 'uk_code');
            $table->comment('组织/公司表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('organizations');
    }
};


