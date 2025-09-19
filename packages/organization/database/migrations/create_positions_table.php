<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('name')->comment('职位名称');
            $table->string('code')->nullable()->index()->comment('职位编码');
            $table->string('sequence')->nullable()->index()->comment('职位序列/通道');
            $table->unsignedInteger('level')->nullable()->index()->comment('职级/排序');
            $table->unsignedBigInteger('parent_id')->nullable()->index()->comment('父级职位ID');
            $table->text('description')->nullable()->comment('职位描述');
            $table->string('status')->default('enable')->comment('状态：enable/disable');
            $table->timestamps();
            $table->comment('职位表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('positions');
    }
};


