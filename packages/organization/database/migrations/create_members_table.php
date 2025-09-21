<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Organization\Domain\Models\Enums\MemberStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('org_id')->default(0)->comment('组织ID');
            $table->string('member_no')->comment('账号');
            $table->string('name')->comment('姓名');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('mobile')->nullable()->comment('*手机号');
            $table->string('email')->nullable()->comment('*邮箱');
            $table->string('password')->nullable()->comment('密码');
            $table->string('gender')->nullable()->comment('性别');
            $table->string('telephone')->nullable()->comment('电话');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('biography')->nullable()->comment('个人介绍');

            $table->timestamp('hired_at')->nullable()->comment('入职时间');
            $table->timestamp('resigned_at')->nullable()->comment('离职时间');
            $table->enum('status', MemberStatusEnum::values())->default(MemberStatusEnum::ACTIVE->value)->comment(MemberStatusEnum::comments('状态'));
            $table->unsignedInteger('position_id')->nullable()->comment('职位ID');
            $table->unsignedBigInteger('leader_id')->nullable()->comment('上级ID');
            $table->unsignedBigInteger('main_department_id')->nullable()->comment('主部门ID');
            $table->json('departments')->nullable()->comment('当前有效部门ID集合(冗余)');
            $table->operator();

            // 索引定义
            $table->unique(['org_id','member_no'],'uk_org_member_no');
            $table->index('org_id', 'idx_org_id');
            $table->index('mobile', 'idx_mobile');
            $table->index('email', 'idx_email');
            $table->index('status', 'idx_status');
            $table->index('position_id', 'idx_position_id');
            $table->index('leader_id', 'idx_leader_id');
            $table->index('main_department_id', 'idx_main_department_id');
            $table->unique('member_no', 'uk_member_no');
            $table->comment('成员表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('members');
    }
};


