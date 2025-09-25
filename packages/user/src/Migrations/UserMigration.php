<?php

namespace RedJasmine\User\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\AccountTypeEnum;

class UserMigration extends \Illuminate\Database\Migrations\Migration
{
    protected string $name  = 'user';
    protected string $label = '用户';

    public function up(): void
    {
        Schema::dropIfExists(Str::plural($this->name));
        Schema::create(Str::plural($this->name), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('account_type', 64)->nullable()->comment(AccountTypeEnum::comments('账号类型'));
            $table->string('status')->default(UserStatusEnum::ACTIVATED)->comment(UserStatusEnum::comments('状态'));
            $table->string('name', 64)->comment('账号');
            $table->string('phone')->nullable()->comment('*手机号');
            $table->string('email')->nullable()->comment('*邮箱');
            $table->string('password')->nullable()->comment('密码');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('realname')->nullable()->comment('姓名');
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
            $table->timestamp('cancel_time')->nullable()->comment('注销时间');
            $table->rememberToken();
            $table->string('invitation_code', 64)->comment('邀请码')->nullable();
            $table->userMorphs('inviter', '邀请人');
            $table->operator();

            $table->index(['name'], 'idx_name');
            $table->index(['phone'], 'idx_phone');
            $table->index(['email'], 'idx_email');

            $table->comment($this->label);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Str::plural($this->name));
    }
}
