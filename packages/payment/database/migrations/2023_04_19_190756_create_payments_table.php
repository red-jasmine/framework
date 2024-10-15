<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('表ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');

            $table->string('owner_type', 20)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者UID');
            $table->string('owner_nickname', 64)->nullable()->comment('所属者昵称');

            // 系统下应用类型
            $table->unsignedBigInteger('app_id')->default(1)->comment('系统内应用ID');
            $table->unsignedBigInteger('app_order_no')->nullable()->comment('应用订单号');
            $table->string('app_order_type', 30)->nullable()->comment('应用订单类型');
            $table->string('subject')->nullable()->comment('交易标题');
            $table->string('description')->nullable()->comment('说明');

            // 支付渠道信息
            $table->string('channel_type')->nullable()->comment('支付渠道');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户ID');
            $table->string('channel_sub_merchant_id', 50)->nullable()->comment('渠道子商户ID');
            $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
            $table->string('channel_app_sub_id')->nullable()->comment('渠道子商户应用ID');
            $table->string('channel_trade_no', 64)->nullable()->comment('渠道支付单号');
            // 支付金额
            $table->decimal('total_amount', 10)->default(0)->comment('交易金额');
            $table->decimal('tax_amount', 10)->default(0)->comment('手续费');
            $table->decimal('pay_amount', 10)->default(0)->comment('实付金额');
            $table->decimal('refund_amount', 10)->default(0)->comment('退款金额');
            $table->decimal('channel_fee_ratio', 10)->default(0)->comment('渠道手续费率');
            $table->decimal('channel_fee', 10)->default(0)->comment('渠道手续费');
            // 状态类
            $table->unsignedTinyInteger('status')->default(0)->comment('支付状态');

            $table->timestamp('create_time')->nullable()->comment('创建时间');
            $table->timestamp('expired_time')->nullable()->comment('过期时间');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('payments');
    }
};
