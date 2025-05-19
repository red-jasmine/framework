<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('captchas', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('app', 32)->comment('应用');
            $table->string('type', 32)->comment('识别码');
            $table->string('notifiable_type', 32)->comment(NotifiableTypeEnum::comments('通知人类型'));
            $table->string('notifiable_id')->comment('接受通知人ID');
            $table->string('method',64)->comment('方式');
            $table->string('code', 10)->comment('验证码');
            $table->string('status')->default(CaptchaStatusEnum::WAIT)->comment(CaptchaStatusEnum::comments('状态'));
            $table->timestamp('exp_time')->nullable()->comment('过期时间');
            $table->timestamp('use_time')->nullable()->comment('使用时间');
            $table->string('send_status')->default(CaptchaSendStatusEnum::WAIT)->comment(CaptchaSendStatusEnum::comments('发送状态'));
            $table->timestamp('send_time')->nullable()->comment('发送时间');
            $table->string('channel')->nullable()->comment('发送渠道');
            $table->string('channel_no')->nullable()->comment('渠道流水号');
            $table->string('channel_message')->nullable()->comment('渠道ID');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->index(['notifiable_id', 'type', 'app', 'notifiable_type'], 'idx_notifiable');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('captchas');
    }
};
