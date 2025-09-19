<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('org_id')->default(0)->index()->comment('组织ID');
            $table->string('member_no')->unique()->comment('成员编号/工号');
            $table->string('name')->comment('姓名');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('mobile')->nullable()->index()->comment('手机号');
            $table->string('email')->nullable()->index()->comment('邮箱');
            $table->string('gender')->nullable()->comment('性别');
            $table->string('telephone')->nullable()->comment('座机');
            $table->timestamp('hired_at')->nullable()->comment('入职时间');
            $table->timestamp('resigned_at')->nullable()->comment('离职时间');
            $table->string('status')->default('active')->index()->comment('状态：active/probation/resigned');
            $table->string('position_name')->nullable()->comment('当前主职位名称(冗余)');
            $table->unsignedInteger('position_level')->nullable()->index()->comment('当前主职位级别(冗余)');
            $table->unsignedBigInteger('main_department_id')->nullable()->index()->comment('当前主部门ID(冗余)');
            $table->json('departments')->nullable()->comment('当前有效部门ID集合(冗余)');
            $table->timestamps();
            $table->comment('成员表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('members');
    }
};


