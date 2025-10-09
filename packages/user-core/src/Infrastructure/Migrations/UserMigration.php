<?php

namespace RedJasmine\UserCore\Infrastructure\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RedJasmine\UserCore\Domain\Enums\AccountTypeEnum;
use RedJasmine\UserCore\Domain\Enums\UserStatusEnum;

class UserMigration extends Migration
{
    protected string $name  = 'user';
    protected string $label = '用户';


    protected function getTableName():string
    {
        return Str::plural($this->name);
    }
    public function up(): void
    {
        Schema::dropIfExists($this->getTableName());
        Schema::create($this->getTableName(), function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->enum('account_type',AccountTypeEnum::values())->default(AccountTypeEnum::PERSONAL)->comment(AccountTypeEnum::comments('账号类型'));
            $table->enum('status',UserStatusEnum::values())->default(UserStatusEnum::ACTIVATED)->comment(UserStatusEnum::comments('状态'));

            // 账号信息
            $table->string('name', 64)->comment('帐号');
            $table->string('phone')->nullable()->comment('*手机号');
            $table->string('email')->nullable()->comment('*邮箱');
            $table->string('password')->nullable()->comment('密码');
            $table->rememberToken();

            // 基础信息
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


            // 激活时间
            $table->timestamp('activated_at')->nullable()->comment('激活时间');
            // 注册时间
            $table->timestamp('registered_at')->nullable()->comment('注册时间');
            // 注销时间
            $table->timestamp('canceled_at')->nullable()->comment('注销时间');
            // 邀请人
            $table->userMorphs('inviter', '邀请人');

            // 活跃时间
            $table->string('ip')->nullable()->comment('IP');
            $table->timestamp('active_at')->nullable()->comment('活跃时间');

            $table->operator();

            $table->index(['name'], 'idx_name');
            $table->index(['phone'], 'idx_phone');
            $table->index(['email'], 'idx_email');

            $table->comment($this->label);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName());
    }
}
