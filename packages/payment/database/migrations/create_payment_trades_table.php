<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            // 服务商信息 TODO
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');
            $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
            $table->string('merchant_trade_no')->comment('商户单号');
            $table->string('merchant_order_no')->nullable()->comment('商户原始订单号');

            $table->string('subject')->nullable()->comment('交易标题');
            $table->string('description')->nullable()->comment('说明');
            $table->string('amount_currency')->comment('金额货币');
            $table->bigInteger('amount_value')->default(0)->comment('金额值');

            // 支付渠道
            $table->unsignedBigInteger('payment_channel_app_id')->nullable()->comment('系统内渠道应用ID');
            $table->string('channel_code')->nullable()->comment('支付渠道');
            $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
            $table->string('channel_trade_no')->nullable()->comment('渠道支付单号');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户号');
            $table->string('channel_product_code')->nullable()->comment('支付产品CODE');

            // 支付者信息
            $table->string('payer_type')->nullable()->comment('支付者类型');
            $table->string('payer_user_id', 64)->nullable()->comment('支付者ID');
            $table->string('payer_open_id', 64)->nullable()->comment('支付者OpenId');
            $table->string('payer_name', 64)->nullable()->comment('支付者名称');
            $table->string('payer_account', 64)->nullable()->comment('支付者账号');
            $table->string('payment_amount_currency')->nullable()->comment('支付金额货币');
            $table->bigInteger('payment_amount_value')->default(0)->comment('支付金额值');

            $table->integer('channel_transaction_fee_rate')->default(0)->comment('渠道手续费率');
            $table->string('channel_transaction_fee_currency')->nullable()->comment('渠道交易费货币');
            $table->unsignedBigInteger('channel_service_fee_value')->default(0)->comment('渠道服务费值');


            $table->string('receipt_amount_currency')->nullable()->comment('实收金额货币');
            $table->bigInteger('receipt_amount_value')->default(0)->comment('实收金额值');

            $table->unsignedTinyInteger('refunds_count')->default(0)->comment('退款次数');
            $table->string('refund_amount_currency')->nullable()->comment('实收金额货币');
            $table->unsignedBigInteger('refund_amount_value')->default(0)->comment('退款金额值');
            $table->unsignedBigInteger('refunding_amount_value')->default(0)->comment('退款金额值');

            $table->string('settle_amount_currency')->nullable()->comment('结算货币');
            $table->bigInteger('settle_amount_value')->default(0)->comment('结算金额');

            $table->string('status')->comment(TradeStatusEnum::comments('状态'));
            $table->string('notify_status')->nullable()->comment(NotifyStatusEnum::comments('异步通知状态'));
            // 场景信息
            $table->string('scene_code')->nullable()->comment('支付场景');
            $table->string('method_code')->nullable()->comment('支付方式');
            // 门店信息
            $table->string('store_type')->nullable()->comment('门店类型');
            $table->string('store_id')->nullable()->comment('门店ID');
            $table->string('store_name')->nullable()->comment('门店名称');
            // 平台手续费率
            $table->integer('platform_transaction_fee_rate')->default(0)->comment('平台手续费率');
            $table->string('platform_transaction_fee_currency')->nullable()->comment('平台交易费货币');
            $table->unsignedBigInteger('platform_service_fee_value')->default(0)->comment('平台服务费值');

            $table->timestamp('expired_time')->nullable()->comment('过期时间');
            $table->timestamp('create_time')->nullable()->comment('创建时间');
            $table->timestamp('paying_time')->nullable()->comment('发起支付时间');
            $table->timestamp('paid_time')->nullable()->comment('支付时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamp('settle_time')->nullable()->comment('结算时间');
            $table->timestamp('notify_time')->nullable()->comment('异步通知时间');

            $table->nullableMorphs('creator', 'idx_creator');
            $table->nullableMorphs('updater', 'idx_updater');
            $table->timestamps();
            $table->comment('支付-支付单');

            $table->unique([ 'merchant_app_id', 'merchant_order_no' ], 'uk_merchant_order');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades');
    }
};
