<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix') . 'payment_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('表ID');

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

            $table->string('cashier_type')->nullable()->comment('收银类型'); // WEB,H5,APP,小程序
            $table->string('cashier_type')->nullable()->comment('收银类型');

            $table->string('currency')->comment('货币');

            // 渠道
            $table->decimal('amount')->default(0)->comment('金额');
            // 实付金额 + 优惠 = 金额
            $table->decimal('payment_amount')->default(0)->comment('实付金额');
            $table->decimal('discount_amount')->default(0)->comment('优惠金额');
            $table->decimal('receipt_amount')->default(0)->comment('实收金额');
            $table->decimal('fee_rate', 10)->default(0)->comment('手续费率');
            $table->decimal('fee', 10)->default(0)->comment('手续费');


            $table->decimal('invoice_amount')->default(0)->comment('开票金额');


            $table->decimal('service_amount', 10)->default(0)->comment('技术服务费');


            // 状态类
            $table->string('status')->comment('支付状态');

            $table->timestamp('create_time')->nullable()->comment('创建时间');
            $table->timestamp('expired_time')->nullable()->comment('过期时间');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');


            $table->string('request_url')->nullable()->comment('请求地址');
            $table->string('return_url')->nullable()->comment('成功重定向地址');
            $table->string('notify_url')->nullable()->comment('业务通知地址');
            $table->json('pass_back_params')->nullable()->comment('回传参数');
            $table->json('extends')->nullable()->comment('扩展参数');
            $table->json('detail')->nullable()->comment('支付明细');
            $table->timestamps();
            $table->comment('支付-支付单');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix') . 'payment_orders');
    }
};
