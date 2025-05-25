<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('order_refunds',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->string('app_id', 64)->comment('应用ID');
                $table->string('refund_no', 64)->unique()->comment('售后单号');
                $table->string('order_no', 64)->comment('订单号');
                $table->string('seller_type', 32)->comment('卖家类型');
                $table->string('seller_id', 64)->comment('卖家ID');
                $table->string('seller_nickname')->nullable()->comment('卖家昵称');
                $table->string('buyer_type', 32)->comment('买家类型');
                $table->string('buyer_id', 64)->comment('买家类型');
                $table->string('buyer_nickname')->nullable()->comment('买家昵称');

                // 售后
                $table->string('refund_type', 32)->comment(RefundTypeEnum::comments('售后类型'));
                $table->string('phase', 32)->comment(RefundPhaseEnum::comments('售后阶段'));
                $table->boolean('has_good_return')->default(false)->comment('是否需要退货');
                $table->string('reason')->nullable()->comment('原因');
                $table->string('refund_status', 32)->comment(RefundStatusEnum::comments('退款状态'));

                // 订单数据
                $table->string('order_type', 32)->comment(OrderTypeEnum::comments('订单类型'));

                // 金额
                $table->string('currency', 10)->default('CNY')->comment('货币');
                $table->decimal('price', 12)->default(0)->comment('价格');
                $table->decimal('cost_price', 12)->default(0)->comment('成本价格');
                $table->decimal('product_amount', 12)->default(0)->comment('商品金额');
                $table->decimal('tax_amount', 12)->default(0)->comment('税费');
                $table->decimal('discount_amount', 12)->default(0)->comment('商品优惠');
                $table->decimal('payable_amount', 12)->default(0)->comment('应付金额');
                $table->decimal('payment_amount', 12)->default(0)->comment('实付金额');
                $table->decimal('divided_payment_amount', 12)->default(0)->comment('分摊后实际付款金额');

                $table->string('shipping_status', 32)->nullable()->comment(ShippingStatusEnum::comments('发货状态'));


                $table->string('good_status', 32)->nullable()->comment(RefundGoodsStatusEnum::comments('货物状态'));

                $table->string('outer_refund_id', 64)->nullable()->comment('外部退款单号');


                $table->decimal('freight_amount', 12)->default(0)->comment('运费');
                $table->decimal('refund_amount', 12)->default(0)->comment('退款金额');
                $table->decimal('total_refund_amount', 12)->default(0)->comment('总退款金额'); // 退商品金额 + 邮费


                $table->timestamp('created_time')->nullable()->comment('创建时间');
                $table->timestamp('end_time')->nullable()->comment('完结时间');
                $table->string('seller_custom_status')->nullable()->comment('卖家自定义状态');
                $table->unsignedTinyInteger('star')->nullable()->comment('加星');
                $table->unsignedTinyInteger('urge')->nullable()->comment('催单');
                $table->timestamp('urge_time')->nullable()->comment('催单时间');
                $table->operator();
                $table->softDeletes();
                $table->comment('订单-退款表');


                $table->index(['seller_id', 'seller_type', 'refund_status'], 'idx_seller');
                $table->index(['buyer_id', 'buyer_type', 'refund_status'], 'idx_buyer');
                $table->comment('订单-商品表');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('order_refunds');
    }
};
