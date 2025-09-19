<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('org_id')->default(0)->index()->comment('组织ID');
            $table->unsignedBigInteger('parent_id')->nullable()->index()->comment('父级部门ID');
            $table->string('name')->comment('部门名称');
            $table->string('short_name')->nullable()->comment('部门简称');
            $table->string('code')->nullable()->comment('部门编码');
            $table->unsignedInteger('sort')->default(0)->comment('同级排序');
            $table->string('status')->default('enable')->comment('状态：enable/disable');
            $table->timestamps();
            $table->comment('部门表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('departments');
    }
};


