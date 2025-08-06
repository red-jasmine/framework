<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'payment_refunds',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('biz')->comment('业务标识');
                $table->string('refund_no')->unique()->comment('退款单号');
                $table->unsignedBigInteger('trade_id')->comment('交易ID');
                $table->string('trade_no')->comment('交易号');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
                $table->string('merchant_refund_no')->comment('商户退款单号'); // 交易号下唯一
                $table->string('merchant_trade_no')->comment('商户交易单号');
                $table->string('merchant_trade_order_no')->nullable()->comment('商户交易原始订单号');
                $table->string('merchant_refund_order_no')->nullable()->comment('商户原始退款订单号');
                $table->unsignedBigInteger('system_channel_app_id')->comment('系统内渠道应用ID');
                $table->string('channel_code')->comment('支付渠道');
                $table->string('channel_app_id')->comment('渠道应用ID');
                $table->string('channel_trade_no')->comment('渠道支付单号');
                $table->string('channel_merchant_id')->nullable()->comment('渠道商户号');
                $table->string('channel_refund_no')->nullable()->comment('渠道退款单号');
                $table->string('refund_amount_currency')->comment('货币');
                $table->unsignedBigInteger('refund_amount_amount')->default(0)->comment('金额');
                $table->string('refund_reason')->comment('商户原始退款单号');
                $table->string('refund_status')->comment(RefundStatusEnum::comments('退款状态'));
                $table->string('notify_status')->nullable()->comment(NotifyStatusEnum::comments('异步通知状态'));
                $table->timestamp('create_time')->nullable()->comment('创建时间');
                $table->timestamp('refund_time')->nullable()->comment('退款时间');
                $table->timestamp('finish_time')->nullable()->comment('结束时间');

                $table->operator();
                // 一个交易下  商户退款单号是唯一的
                $table->unique([ 'trade_no', 'merchant_refund_no' ], 'uk_trade_merchant_refund_no');
                $table->comment('支付-退款单');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'payment_refunds');
    }
};
