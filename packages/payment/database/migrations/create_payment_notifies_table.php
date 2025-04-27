<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_notifies',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('商户ID');
                $table->unsignedBigInteger('provider_id')->nullable()->comment('服务商ID');
                $table->string('notify_no')->unique()->comment('通知序号');
                $table->string('notify_type')->comment('通知类型');
                $table->string('business_type')->comment('业务类型');
                $table->string('business_no')->comment('业务单号');
                $table->string('url')->comment('通知地址');
                $table->json('body')->nullable()->comment('通知请求');
                $table->json('response')->nullable()->comment('通知响应');
                $table->unsignedTinyInteger('notify_count')->default(0)->comment('通知次数');
                $table->timestamp('notify_time')->nullable()->comment('通知时间');
                $table->string('status')->default(NotifyStatusEnum::WAIT)->comment(NotifyStatusEnum::comments('状态'));
                $table->timestamps();
                $table->comment('支付-通知记录');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_notifies');
    }
};
