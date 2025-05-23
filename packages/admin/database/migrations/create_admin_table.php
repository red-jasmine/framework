<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\UserTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        $name  = 'admin';
        $label = '管理员';
        Schema::create(\Illuminate\Support\Str::plural($name), function (Blueprint $table) use ($label) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('type', 64)->nullable()->comment(UserTypeEnum::comments('类型'));
            $table->string('status')->default(UserStatusEnum::ACTIVATED)->comment(UserStatusEnum::comments('状态'));
            $table->string('name', 64)->comment('帐号');
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


            $table->unsignedBigInteger('group_id')->nullable()->comment('分组ID');


            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();


            $table->index(['name'], 'idx_name');
            $table->index(['phone'], 'idx_phone');
            $table->index(['email'], 'idx_email');
            $table->index(['group_id'], 'idx_owner_group_id');
            $table->comment($label);

        });

        Schema::create($name.'_groups', function (Blueprint $table) use ($label) {
            $table->category($label.'分组');

        });

        Schema::create($name.'_tags', function (Blueprint $table) use ($label) {
            $table->category($label.'标签');
        });

        Schema::create($name.'_tag_pivot', function (Blueprint $table) use ($label) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
            $table->index('owner_id', 'idx_owner');
            $table->index('tag_id', 'idx_tag');
            $table->comment($label.'标签关联表');
        });
    }

    public function down() : void
    {
        $name = 'admin';
        Schema::dropIfExists(\Illuminate\Support\Str::plural($name));

        Schema::dropIfExists('user_groups');

        Schema::dropIfExists('user_tags');

        Schema::dropIfExists('user_tag_pivot');
    }

};
