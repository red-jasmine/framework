<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\InvoiceStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderRefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('order_product_no', 64)->unique()->comment('商品单号');
            $table->string('biz', 64)->comment('业务线');
            $table->string('order_no', 64)->comment('订单号');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->string('seller_id', 64)->comment('卖家ID');
            $table->string('seller_nickname')->nullable()->comment('卖家昵称');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->string('buyer_id', 64)->comment('买家类型');
            $table->string('buyer_nickname')->nullable()->comment('买家昵称');
            //  订单来源 如：活动、购物车、商品、其他等
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


            // 商品基本信息
            $table->enum('order_product_type', ProductTypeEnum::values())->comment(ProductTypeEnum::comments('商品类型'));
            $table->enum('shipping_type', ShippingTypeEnum::values())->comment(ShippingTypeEnum::comments('发货类型'));

            // 商品身份信息
            $table->string('product_type', 32)->comment('商品源类型');
            $table->unsignedBigInteger('product_id')->comment('商品源ID');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('规格ID');
            // 商品信息
            $table->string('title')->comment('标题');
            $table->string('sku_name')->nullable()->comment('规格名称');
            $table->string('image')->nullable()->comment('图片');
            $table->string('spu', 64)->nullable()->comment('外部商品编码');
            $table->string('sku', 64)->nullable()->comment('外部规格编码');
            $table->string('barcode', 64)->nullable()->comment('条形码');
            $table->unsignedBigInteger('unit_quantity')->default(1)->comment('单位数量');
            $table->string('unit')->nullable()->comment('单位');
            $table->unsignedBigInteger('category_id')->default(0)->comment('类目ID');
            $table->unsignedBigInteger('brand_id')->default(0)->comment('品牌ID');
            $table->unsignedBigInteger('product_group_id')->default(0)->comment('商品分组ID');
            $table->unsignedBigInteger('gift_point')->default(0)->comment('赠送积分');
            $table->string('warehouse_code', 64)->nullable()->comment('仓库编码');
            $table->decimal('tax_rate')->default(0)->comment('税率%');
            // 销售总价  = 销售单价 * 数量
            // 商品金额  = 销售总价 - 优惠
            // 税费金额  = ( 商品金额  ) * 税率
            // 应付金额  = 商品金额 + 服务费 + 运费金额 + 商品税费 - 分摊优惠金额  = 最大退款金额

            $table->unsignedBigInteger('quantity')->default(0)->comment('数量');
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->decimal('price', 12)->default(0)->comment('销售单价');
            $table->decimal('total_price', 12)->default(0)->comment('销售总价');
            $table->decimal('discount_amount', 12)->default(0)->comment('优惠金额');
            $table->decimal('product_amount', 12)->default(0)->comment('商品金额');
            $table->decimal('tax_amount', 12)->default(0)->comment('税费金额');
            $table->decimal('service_amount', 12)->default(0)->comment('服务费金额');
            $table->decimal('freight_amount', 12)->default(0)->comment('分摊运费金额');
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

            // 订单类型 决定流程
            $table->string('order_type', 32)->comment('订单类型');
            // 状态
            $table->enum('order_status', OrderStatusEnum::values())->comment(OrderStatusEnum::comments('订单状态'));
            $table->enum('order_refund_status',
                OrderRefundStatusEnum::values())->nullable()->comment(OrderRefundStatusEnum::comments('订单退款状态'));
            $table->enum('payment_status', PaymentStatusEnum::values())->nullable()->comment(PaymentStatusEnum::comments('付款状态'));
            $table->enum('accept_status', AcceptStatusEnum::values())->nullable()->comment(AcceptStatusEnum::comments('接单状态'));
            $table->enum('shipping_status', ShippingStatusEnum::values())->nullable()->comment(ShippingStatusEnum::comments('发货状态'));
            $table->enum('settlement_status',
                SettlementStatusEnum::values())->nullable()->comment(SettlementStatusEnum::comments('结算状态'));
            $table->enum('rate_status', RateStatusEnum::values())->nullable()->comment(RateStatusEnum::comments('评价状态'));
            $table->enum('invoice_status', InvoiceStatusEnum::values())->nullable()->comment(InvoiceStatusEnum::comments('发票状态'));
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

            // 虚拟商品使用
            $table->timestamp('expiration_date')->nullable()->comment('过期时间');

            $table->unsignedBigInteger('progress')->nullable()->comment('进度');
            $table->unsignedBigInteger('progress_total')->nullable()->comment('进度总数');
            // 卡密专用
            $table->string('contact')->nullable()->comment('联系方式');
            $table->string('password')->nullable()->comment('查询密码');

            $table->string('last_refund_no')->nullable()->comment('最后退款单款单号');

            // 买家外部单号
            $table->string('outer_order_product_id', 64)->nullable()->comment('外部商品单号');

            $table->string('shopping_cart_id', 64)->nullable()->comment('购物车ID');
            // 供应商 TODO

            $table->unsignedBigInteger('batch_no')->default(0)->comment('批次号');
            $table->operator();
            $table->softDeletes();
            $table->index('order_no', 'idx_order');
            $table->index(['seller_id', 'seller_type', 'order_no'], 'idx_seller');
            $table->index(['buyer_id', 'buyer_type', 'order_no'], 'idx_buyer');
            $table->comment('订单-商品表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('order_products');
    }
};
