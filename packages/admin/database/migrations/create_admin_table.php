<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Admin\Domain\Models\Enums\AdminStatusEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminTypeEnum;

return new class extends Migration {
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->comment('ID');
            $table->string('type', 64)->nullable()->comment(AdminTypeEnum::comments('账号类型'));
            $table->string('status')->default(AdminStatusEnum::ACTIVATED)->comment(AdminStatusEnum::comments('状态'));

            $table->string('name', 64)->comment('账号');
            $table->string('phone')->nullable()->comment('*手机号');
            $table->string('email')->nullable()->comment('*邮箱');
            $table->string('password')->nullable()->comment('密码');
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
            $table->timestamp('cancel_time')->nullable()->comment('注销时间');
            $table->rememberToken();


            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name'], 'idx_name');


            $table->comment('管理员表');
        });
    }
};
