<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\UserTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('用户ID');
            $table->string('owner_type', 64)->default('system');
            $table->string('owner_id', 64)->default('system');
            $table->string('type', 64)->nullable()->comment(UserTypeEnum::comments('账号类型'));
            $table->string('name', 64)->comment('账号');
            $table->string('phone')->nullable()->comment('*手机号');
            $table->string('email')->nullable()->comment('*邮箱');
            $table->string('password')->nullable()->comment('密码');
            $table->string('status')->default(UserStatusEnum::ACTIVATED)->comment(UserStatusEnum::comments('状态'));
            $table->string('nickname', 64)->nullable()->comment('昵称');
            $table->string('gender', 32)->nullable()->comment('性别');
            $table->string('avatar')->nullable()->comment('头像');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('biography')->nullable()->comment('个人介绍');
            $table->string('country')->nullable()->comment('国家');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('district')->nullable()->comment('区县');
            $table->string('school')->nullable()->comment('学校');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_active_at')->nullable()->comment('最后活跃时间');
            $table->string('ip')->nullable()->comment('IP');
            $table->unsignedBigInteger('group_id')->nullable()->comment('分组ID');
            $table->rememberToken();
            $table->timestamp('cancel_time')->nullable()->comment('注销时间');
            $table->timestamps();
            $table->index(['owner_type', 'owner_id', 'name'], 'idx_owner_name');
            $table->index(['owner_type', 'owner_id', 'phone'], 'idx_owner_phone');
            $table->index(['owner_type', 'owner_id', 'email'], 'idx_owner_email');
            $table->index(['owner_type', 'owner_id', 'group_id'], 'idx_owner_group_id');
            $table->comment('用户表');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('users');
    }

};
