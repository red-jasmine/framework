<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Organization\Domain\Models\Enums\PositionStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('org_id')->default(0)->comment('组织ID');
            $table->string('name')->comment('职位名称');
            $table->string('code')->nullable()->comment('职位编码');
            $table->string('sequence')->nullable()->comment('职位序列/通道');
            $table->unsignedInteger('level')->nullable()->comment('职级/排序');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父级职位ID');
            $table->text('description')->nullable()->comment('职位描述');
            $table->enum('status', PositionStatusEnum::values())->default(PositionStatusEnum::ENABLE->value)->comment(PositionStatusEnum::comments('状态'));
            $table->operator();

            // 索引定义
            $table->index('org_id', 'idx_org_id');
            $table->index('code', 'idx_code');
            $table->index('sequence', 'idx_sequence');
            $table->index('level', 'idx_level');
            $table->index('parent_id', 'idx_parent_id');
            $table->comment('职位表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('positions');
    }
};


