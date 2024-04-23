<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Order\Domain\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Enums\PayTypeEnum;
use RedJasmine\Order\Domain\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Enums\ShippingTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('订单号');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('seller_nickname')->nullable()->comment('卖家昵称');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家类型');
            $table->string('buyer_nickname')->nullable()->comment('买家昵称');
            $table->string('title')->nullable()->comment('标题');

            $table->enum('order_type', OrderTypeEnum::values())->comment(OrderTypeEnum::comments('订单类型'));
            $table->enum('shipping_type', ShippingTypeEnum::values())->comment(ShippingTypeEnum::comments('发货类型'));
            $table->enum('pay_type', PayTypeEnum::values())->comment(PayTypeEnum::comments('支付方式'));

            $table->enum('order_status',OrderStatusEnum::values())->comment(OrderStatusEnum::comments('订单状态'));
            $table->enum('payment_status', PaymentStatusEnum::values())->default(PaymentStatusEnum::NIL->value)->comment(PaymentStatusEnum::comments('付款状态'));
            $table->enum('shipping_status', ShippingStatusEnum::values())->default(ShippingStatusEnum::NIL->value)->comment(ShippingStatusEnum::comments('发货状态'));
            $table->enum('refund_status', OrderRefundStatusEnum::values())->default(OrderRefundStatusEnum::NIL->value)->comment(OrderRefundStatusEnum::comments('退款状态'));
            $table->enum('rate_status', RateStatusEnum::values())->default(RateStatusEnum::NIL->value)->comment(RateStatusEnum::comments('评价状态'));
            $table->enum('settlement_status', SettlementStatusEnum::values())->default(SettlementStatusEnum::NIL->value)->comment(SettlementStatusEnum::comments('结算状态'));
            $table->string('seller_custom_status', 32)->default('nil')->comment('卖家自定义状态');

            $table->decimal('total_product_amount', 12)->default(0)->comment('商品总金额');
            $table->decimal('total_cost_amount', 12)->default(0)->comment('成本总金额');
            $table->decimal('total_payable_amount', 12)->default(0)->comment('商品应付总金额');
            $table->decimal('freight_amount', 12)->default(0)->comment('运费');
            $table->decimal('discount_amount', 12)->default(0)->comment('订单优惠');
            $table->decimal('payable_amount', 12)->default(0)->comment('应付金额');
            $table->decimal('payment_amount', 12)->default(0)->comment('实付金额');
            $table->decimal('refund_amount', 12)->default(0)->comment('退款金额');
            $table->decimal('total_commission_amount', 12)->default(0)->comment('总佣金');


            $table->timestamp('created_time')->nullable()->comment('创建时间');
            $table->timestamp('payment_time')->nullable()->comment('付款时间');
            $table->timestamp('close_time')->nullable()->comment('关闭时间');
            $table->timestamp('shipping_time')->nullable()->comment('发货时间');
            $table->timestamp('collect_time')->nullable()->comment('揽收时间');
            $table->timestamp('dispatch_time')->nullable()->comment('派送时间');
            $table->timestamp('signed_time')->nullable()->comment('签收时间');
            $table->timestamp('confirm_time')->nullable()->comment('确认时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamp('rate_time')->nullable()->comment('评价时间');
            $table->timestamp('settlement_time')->nullable()->comment('结算时间');

            $table->string('channel_type', 32)->nullable()->comment('渠道类型');
            $table->unsignedBigInteger('channel_id')->nullable()->comment('渠道ID');
            $table->string('store_type', 32)->nullable()->comment('门店类型');
            $table->unsignedBigInteger('store_id')->nullable()->comment('门店ID');
            $table->string('guide_type', 32)->nullable()->comment('导购类型');
            $table->unsignedBigInteger('guide_id')->nullable()->comment('导购ID');


            $table->string('client_type', 32)->nullable()->comment('客户端');
            $table->string('client_version', 32)->nullable()->comment('客户端版本');
            $table->string('client_ip', 32)->nullable()->comment('IP');
            $table->string('source_type', 32)->nullable()->comment('来源类型');
            $table->string('source_id', 32)->nullable()->comment('来源ID');

            $table->string('contact')->nullable()->comment('联系方式');
            $table->string('password')->nullable()->comment('查询密码');

            $table->unsignedTinyInteger('is_seller_delete')->default(0)->comment('卖家删除');
            $table->unsignedTinyInteger('is_buyer_delete')->default(0)->comment('买家删除');
            $table->string('outer_order_id', 64)->nullable()->comment('外部订单号');
            $table->string('cancel_reason')->nullable()->comment('取消原因');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator'); // 创建人
            $table->nullableMorphs('updater'); // 更新人
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('orders');
    }
};
