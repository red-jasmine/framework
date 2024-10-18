<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_').'order_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('商品单号');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('refund_id')->default(0)->comment('退款单ID');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家ID');

            $table->string('amount_type', 32)->comment(AmountTypeEnum::comments('金额类型'));
            $table->decimal('payment_amount', 12)->comment('支付金额');
            $table->string('status', 32)->comment(PaymentStatusEnum::comments('状态'));
            $table->timestamp('payment_time')->nullable()->comment('支付时间');

            $table->string('payment_type', 32)->nullable()->comment('支付单类型');
            $table->unsignedBigInteger('payment_id')->nullable()->comment('支付单 ID');
            $table->string('payment_method')->nullable()->comment('支付方式');
            $table->string('payment_channel')->nullable()->comment('支付渠道');
            $table->string('payment_channel_no')->nullable()->comment('支付渠道单号');

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator'); // 创建人
            $table->nullableMorphs('updater'); // 更新人
            $table->timestamps();
            $table->comment('订单-支付单');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_') .'order_payments');
    }
};
