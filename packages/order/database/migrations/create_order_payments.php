<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('商品单号');

            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家ID');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->string('entity_type')->comment(EntityTypeEnum::comments('对象类型'));
            $table->unsignedBigInteger('entity_id')->comment('对象单号');


            $table->string('amount_type', 32)->comment(AmountTypeEnum::comments('金额类型'));
            $table->decimal('payment_amount', 12)->comment('支付金额');
            $table->string('status', 32)->comment(PaymentStatusEnum::comments('支付状态'));
            $table->timestamp('payment_time')->nullable()->comment('支付时间');

            // 管理第三方支付单 类型 如 对接 支付宝、微信、支付中心
            $table->string('payment_type', 32)->nullable()->comment('支付单类型');
            $table->string('payment_id')->nullable()->comment('支付单 ID');
            // 如 手机支付、扫码支付、被扫
            $table->string('payment_method')->nullable()->comment('支付方式');
            // 支付渠道 如 支付宝、微信、银联等
            $table->string('payment_channel')->nullable()->comment('支付渠道');
            // 对接的第二房单号
            $table->string('payment_channel_no')->nullable()->comment('支付渠道单号');

            $table->string('message')->nullable()->comment('其他信息');

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator'); // 创建人
            $table->nullableMorphs('updater'); // 更新人
            $table->timestamps();
            $table->comment('订单-支付单');


            $table->index([ 'entity_id', 'entity_type' ], 'idx_entity');
            $table->index([ 'seller_id', 'seller_type', 'order_id', ], 'idx_seller');
            $table->index([ 'buyer_id', 'buyer_type', 'order_id', ], 'idx_buyer');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_payments');
    }
};
