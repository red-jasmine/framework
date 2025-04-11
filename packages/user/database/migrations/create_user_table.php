<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use RedJasmine\User\Domain\Enums\UserStatusEnum;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('用户ID');
            $table->string('name', 64)->comment('账号');
            $table->string('mobile', 64)->nullable()->comment('手机号');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('nickname', 64)->nullable()->comment('昵称');
            $table->string('gender', 64)->nullable()->comment('性别');
            $table->string('avatar')->nullable()->comment('头像');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('biography')->nullable()->comment('个人介绍');
            $table->string('type', 64)->nullable()->comment(UserTypeEnum::comments('账号类型'));
            $table->string('status')->default(UserStatusEnum::ACTIVATED)->comment(UserStatusEnum::comments('状态'));
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_active_at')->nullable()->comment('最后活跃时间');
            $table->rememberToken();
            $table->timestamps();
            // 邀请人
            $table->index(['name'], 'idx_name');
            $table->index(['mobile'], 'idx_mobile');
            $table->index(['email'], 'idx_email');
            $table->comment('用户表');

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }

};
