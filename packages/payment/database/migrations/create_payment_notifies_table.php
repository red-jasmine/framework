<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_notifies',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('商户ID');
                $table->unsignedBigInteger('provider_id')->nullable()->comment('服务商ID');
                $table->string('notify_no')->comment('通知序号');
                $table->string('business_type')->comment('业务类型');
                $table->string('business_no')->comment('业务单号');
                $table->string('notify_url')->comment('通知地址');
                $table->json('notify_request')->nullable()->comment('通知请求');
                $table->json('notify_response')->nullable()->comment('通知响应');
                $table->timestamp('notify_count')->default(0)->comment('通知次数');
                $table->timestamp('notify_time')->nullable()->comment('通知时间');
                $table->string('status')->comment(NotifyStatusEnum::comments('状态'));
                $table->timestamps();
                $table->comment('支付-通知记录');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_notifies');
    }
};
