<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatus;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix') . 'payment_trades', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            // 服务商信息
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');
            $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');

            $table->string('subject')->nullable()->comment('交易标题');
            $table->string('description')->nullable()->comment('说明');


            $table->string('channel_merchant_id')->comment('渠道商户号');
            $table->string('channel_app_id')->comment('渠道应用ID');
            $table->string('channel')->nullable()->comment('支付渠道');
            $table->string('channel_trade_no', 64)->nullable()->comment('渠道支付单号');
            $table->string('channel_payer_open_id', 64)->nullable()->comment('支付者');
            $table->string('channel_payer_user_type')->nullable()->comment('支付者类型');
            $table->string('merchant_order_no')->nullable()->comment('商户原始单号');

            // 服务商信息

            $table->string('currency')->comment('货币');
            $table->decimal('amount')->default(0)->comment('金额');
            $table->decimal('channel_fee_rate', 10)->default(0)->comment('渠道手续费率');
            $table->decimal('channel_fee', 10)->default(0)->comment('渠道手续费');
            // 实付金额 + 优惠 = 金额
            $table->decimal('discount_amount')->default(0)->comment('优惠金额');
            $table->decimal('payment_amount')->default(0)->comment('实付金额');

            $table->string('payment_currency')->comment('支付货币');

            $table->decimal('settle_currency')->default(0)->comment('结算货币');
            $table->decimal('receipt_amount')->default(0)->comment('实收金额');


            $table->decimal('invoice_amount')->default(0)->comment('开票金额');
            $table->string('status')->comment(TradeStatus::comments('状态'));
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
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix') . 'payment_trades');
    }
};
