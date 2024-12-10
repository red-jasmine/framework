<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            // 服务商信息 TODO
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');
            $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
            $table->string('merchant_order_no')->comment('商户原始单号');

            $table->string('subject')->nullable()->comment('交易标题');
            $table->string('description')->nullable()->comment('说明');
            $table->string('currency')->comment('货币');
            $table->bigInteger('amount')->default(0)->comment('总金额');
            $table->bigInteger('discount_amount')->default(0)->comment('优惠金额');
            $table->bigInteger('payable_amount')->default(0)->comment('应付金额');
            // 支付渠道
            $table->string('channel_code')->nullable()->comment('支付渠道');
            $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
            $table->string('channel_trade_no', 64)->nullable()->comment('渠道支付单号');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户号');
            $table->string('payment_currency')->nullable()->comment('支付货币');
            $table->bigInteger('payment_amount')->default(0)->comment('支付金额');
            $table->string('receipt_currency')->nullable()->comment('实收货币');
            $table->bigInteger('receipt_amount')->default(0)->comment('实收金额');
            // 支付者信息
            $table->string('payer_type')->nullable()->comment('支付者类型');
            $table->string('payer_user_id', 64)->nullable()->comment('支付者ID');
            $table->string('payer_name', 64)->nullable()->comment('支付者名称');
            $table->string('payer_account', 64)->nullable()->comment('支付者账号');

            $table->bigInteger('channel_fee_rate')->default(0)->comment('渠道手续费率');
            $table->bigInteger('channel_fee')->default(0)->comment('渠道手续费');

            $table->string('settle_currency')->nullable()->comment('结算货币');
            $table->bigInteger('settle_amount')->default(0)->comment('结算金额');

            $table->string('status')->comment(TradeStatusEnum::comments('状态'));
            $table->string('notify_status')->nullable()->comment('异步通知状态');
            // 场景信息
            $table->string('scene_code')->comment('支付场景');
            $table->string('method_code')->comment('支付方式');
            // 门店信息
            $table->string('store_type')->nullable()->comment('门店类型');
            $table->string('store_id')->nullable()->comment('门店ID');
            $table->string('store_name')->nullable()->comment('门店名称');
            $table->timestamp('create_time')->nullable()->comment('创建时间');
            $table->timestamp('expired_time')->nullable()->comment('过期时间');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
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
