<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_refunds', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('trade_id')->comment('交易ID');
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');
            $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
            $table->string('merchant_refund_no')->comment('商户原始退款单号');
            $table->unsignedBigInteger('payment_channel_app_id')->comment('系统内渠道应用ID');
            $table->string('channel_code')->comment('支付渠道');
            $table->string('channel_app_id')->comment('渠道应用ID');
            $table->string('channel_trade_no', 64)->comment('渠道支付单号');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户号');
            $table->string('refund_amount_currency')->comment('货币');
            $table->decimal('refund_amount_amount')->default(0)->comment('金额');
            $table->string('refund_reason')->comment('商户原始退款单号');

            $table->string('status')->comment(RefundStatusEnum::comments('退款状态'));
            $table->string('notify_status')->nullable()->comment('异步通知状态');
            $table->timestamp('create_time')->nullable()->comment('创建时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->nullableMorphs('creator', 'idx_creator');
            $table->nullableMorphs('updater', 'idx_updater');
            $table->timestamps();
            $table->comment('支付-退款');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_refunds');
    }
};
