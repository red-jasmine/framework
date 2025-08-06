<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\InvoiceStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('order_no', 64)->unique()->comment('订单号');
            $table->string('biz', 64)->comment('业务线');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->string('seller_id', 64)->comment('卖家ID');
            $table->string('seller_nickname')->nullable()->comment('卖家昵称');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->string('buyer_id', 64)->comment('买家类型');
            $table->string('buyer_nickname')->nullable()->comment('买家昵称');
            //  内部来源 如：活动、购物车、商品、其他等
            $table->string('source_type', 32)->nullable()->comment('订单来源');
            $table->string('source_id', 32)->nullable()->comment('来源ID');
            // 门店 、渠道 、导购、客户端
            $table->string('store_type', 32)->nullable()->comment('门店类型');
            $table->unsignedBigInteger('store_id')->nullable()->comment('门店ID');
            $table->string('store_nickname')->nullable()->comment('门店名称');
            $table->string('channel_type', 32)->nullable()->comment('渠道类型');
            $table->unsignedBigInteger('channel_id')->nullable()->comment('渠道ID');
            $table->string('channel_nickname')->nullable()->comment('渠道名称');
            $table->string('guide_type', 32)->nullable()->comment('导购类型');
            $table->unsignedBigInteger('guide_id')->nullable()->comment('导购ID');
            $table->string('guide_nickname')->nullable()->comment('导购名称');
            $table->string('client_type', 32)->nullable()->comment('客户端类型');
            $table->string('client_version', 32)->nullable()->comment('客户端版本');
            $table->string('client_ip', 32)->nullable()->comment('IP');

            // 订单类型
            $table->string('order_type', 32)->comment('订单类型');

            $table->string('shipping_type', 32)->comment(ShippingTypeEnum::comments('发货类型'));
            // 状态
            // 总流程状态
            $table->string('order_status', 32)->comment(OrderStatusEnum::comments('订单状态'));
            $table->string('order_refund_status', 32)->nullable()->comment(OrderRefundStatusEnum::comments('订单退款状态'));
            // 步骤状态
            $table->string('payment_status', 32)->nullable()->comment(PaymentStatusEnum::comments('付款状态'));
            $table->string('accept_status', 32)->nullable()->comment(AcceptStatusEnum::comments('接单状态'));
            $table->string('shipping_status', 32)->nullable()->comment(ShippingStatusEnum::comments('发货状态'));
            $table->string('settlement_status', 32)->nullable()->comment(SettlementStatusEnum::comments('结算状态'));
            $table->string('invoice_status', 32)->nullable()->comment(InvoiceStatusEnum::comments('发票状态'));
            $table->string('rate_status', 32)->nullable()->comment(RateStatusEnum::comments('评价状态'));
            $table->string('seller_custom_status', 32)->nullable()->comment('卖家自定义状态');
            // 时间
            $table->timestamp('created_time')->nullable()->comment('创建时间');
            $table->timestamp('payment_time')->nullable()->comment('付款时间');
            $table->timestamp('accept_time')->nullable()->comment('接单时间');
            $table->timestamp('shipping_time')->nullable()->comment('发货时间');
            $table->timestamp('signed_time')->nullable()->comment('签收时间');
            $table->timestamp('confirm_time')->nullable()->comment('确认时间');
            $table->timestamp('close_time')->nullable()->comment('关闭时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamp('rate_time')->nullable()->comment('评价时间');
            $table->timestamp('settlement_time')->nullable()->comment('结算时间');
            $table->timestamp('collect_time')->nullable()->comment('揽收时间');
            $table->timestamp('dispatch_time')->nullable()->comment('派送时间');


            $table->unsignedBigInteger('quantity')->default(0)->comment('数量');
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->decimal('price', 12)->default(0)->comment('销售单价');
            $table->decimal('total_price', 12)->default(0)->comment('总销售总价');// 汇总
            $table->decimal('product_amount', 12)->default(0)->comment('总商品金额'); // 汇总
            $table->decimal('service_amount', 12)->default(0)->comment('总服务费金额'); // 汇总
            $table->decimal('tax_amount', 12)->default(0)->comment('总税费金额'); // 汇总
            $table->decimal('discount_amount', 12)->default(0)->comment('订单优惠金额');
            $table->decimal('freight_amount', 12)->default(0)->comment('订单运费金额'); // 需要分摊
            $table->decimal('divided_discount_amount', 12)->default(0)->comment('分摊优惠金额');
            $table->decimal('payable_amount', 12)->default(0)->comment('买家应付金额');
            $table->decimal('payment_amount', 12)->default(0)->comment('买家实付金额');
            $table->decimal('refund_amount', 12)->default(0)->comment('买家退款金额');
            // 结算类
            $table->decimal('commission_amount', 12)->default(0)->comment('佣金');
            $table->decimal('seller_discount_amount', 12)->default(0)->comment('卖家优惠金额');
            $table->decimal('platform_discount_amount', 12)->default(0)->comment('平台优惠金额');
            $table->decimal('platform_service_amount', 12)->default(0)->comment('平台服务费');
            $table->decimal('receivable_amount', 12)->default(0)->comment('卖家应收金额');
            $table->decimal('received_amount', 12)->default(0)->comment('卖家实收金额');
            // 统计类
            $table->decimal('cost_price', 12)->default(0)->comment('成本单价');
            $table->decimal('total_cost_price', 12)->default(0)->comment('成本总价');


            // 由此可可以控制 各类订单类型 的确认时间 如：等待拼单时间、拼团时间、酒店单确认时间等 分钟
            $table->bigInteger('payment_timeout')->default(0)->comment('支付超时');
            $table->bigInteger('accept_timeout')->default(0)->comment('接单超时');
            $table->bigInteger('confirm_timeout')->default(0)->comment('确认超时');
            $table->bigInteger('rate_timeout')->default(0)->comment('评价超时');


            $table->string('title')->nullable()->comment('标题');

            // 买家外部单号
            $table->string('outer_order_id', 64)->nullable()->comment('外部订单号');
            // 卖家 供应商相关 TODO


            $table->unsignedTinyInteger('star')->nullable()->comment('加星');
            $table->unsignedTinyInteger('urge')->nullable()->comment('催单');
            $table->timestamp('urge_time')->nullable()->comment('催单时间');
            $table->boolean('is_seller_delete')->default(false)->comment('卖家删除');
            $table->boolean('is_buyer_delete')->default(false)->comment('买家删除');

            $table->string('cancel_reason')->nullable()->comment('取消原因');
            $table->operator();
            $table->softDeletes();
            $table->comment('订单表');

            $table->index(['buyer_type', 'buyer_id', 'order_status'], 'idx_buyer');
            $table->index(['seller_type', 'seller_id', 'order_status'], 'idx_seller');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('orders');
    }
};
