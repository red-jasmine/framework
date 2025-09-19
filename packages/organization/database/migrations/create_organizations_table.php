<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('parent_id')->nullable()->index()->comment('父级组织ID');
            $table->string('name')->comment('组织名称');
            $table->string('short_name')->nullable()->comment('组织简称');
            $table->string('code')->nullable()->unique()->comment('组织编码/统一社会信用代码');
            $table->unsignedInteger('sort')->default(0)->comment('同级排序');
            // NestedSet 或 path/depth
            $table->string('status')->default('enable')->comment('状态：enable/disable');
            $table->timestamps();
            $table->comment('组织/公司表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('organizations');
    }
};


