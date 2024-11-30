<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix','jasmine_') . 'payment_trades', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            // 服务商信息 TODO
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');
            $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
            $table->string('merchant_order_no')->comment('商户原始单号');
            // 服务商信息
            $table->string('currency')->comment('货币');
            $table->bigInteger('amount')->default(0)->comment('金额');


            $table->string('subject')->nullable()->comment('交易标题');
            $table->string('description')->nullable()->comment('说明');

            $table->string('channel')->nullable()->comment('支付渠道');
            $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
            $table->string('channel_trade_no', 64)->nullable()->comment('渠道支付单号');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户号');
            // 支付者信息
            $table->string('channel_payer_user_type')->nullable()->comment('支付者类型');
            $table->string('channel_payer_open_id', 64)->nullable()->comment('支付者OpenId');


            $table->bigInteger('channel_fee_rate')->default(0)->comment('渠道手续费率');
            $table->bigInteger('channel_fee')->default(0)->comment('渠道手续费');
            // 实付金额 + 优惠 = 金额
            $table->bigInteger('discount_amount')->default(0)->comment('优惠金额');
            $table->bigInteger('payment_amount')->default(0)->comment('实付金额');

            $table->string('payment_currency')->nullable()->comment('支付货币');

            $table->bigInteger('settle_currency')->default(0)->comment('结算货币');
            $table->bigInteger('receipt_amount')->default(0)->comment('实收金额');


            $table->bigInteger('invoice_amount')->default(0)->comment('开票金额');
            $table->string('status')->comment(TradeStatusEnum::comments('状态'));
            // 场景信息

            // 门店信息
            $table->string('store_type')->nullable()->comment('门店类型');
            $table->string('store_id')->nullable()->comment('门店ID');
            $table->string('store_name')->nullable()->comment('门店名称');
            // 结算信息
            // 操作人员信息
            // 状态类
            $table->timestamp('create_time')->nullable()->comment('创建时间');
            $table->timestamp('expired_time')->nullable()->comment('过期时间');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamp('settle_time')->nullable()->comment('结算时间');


            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->comment('支付-支付单');

            $table->unique([ 'merchant_app_id', 'merchant_order_no' ], 'uk_merchant_order');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix','jasmine_') . 'payment_trades');
    }
};
