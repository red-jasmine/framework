<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;


return new class extends Migration {
    public function up() : void
    {
        Schema::create('order_refunds',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('refund_no', 64)->unique()->comment('售后单号');
                // 订单数据
                $table->string('order_no', 64)->comment('订单号');
                $table->string('biz', 64)->comment('业务线');
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

                // 订单类型
                $table->string('order_type', 32)->comment('订单类型');


                // 订单商品数据
                $table->string('order_product_no', 64)->comment('商品单号');

                // 商品基本信息
                $table->enum('order_product_type', ProductTypeEnum::values())->comment(ProductTypeEnum::comments('订单商品类型'));
                $table->enum('shipping_type', ShippingTypeEnum::values())->comment(ShippingTypeEnum::comments('发货类型'));
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
                $table->decimal('tax_rate')->default(0)->comment('税率%');


                // 金额
                $table->unsignedBigInteger('quantity')->default(0)->comment('数量');
                $table->string('currency', 3)->default('CNY')->comment('货币');
                $table->decimal('price', 12)->default(0)->comment('销售单价');
                $table->decimal('total_price', 12)->default(0)->comment('销售总价');// 汇总
                $table->decimal('discount_amount', 12)->default(0)->comment('优惠金额');
                $table->decimal('product_amount', 12)->default(0)->comment('商品金额'); // 汇总
                $table->decimal('tax_amount', 12)->default(0)->comment('税费金额'); // 汇总
                $table->decimal('service_amount', 12)->default(0)->comment('服务费金额'); // 汇总
                $table->decimal('freight_amount', 12)->default(0)->comment('运费金额'); // 需要分摊
                $table->decimal('divided_discount_amount', 12)->default(0)->comment('分摊优惠金额');
                $table->decimal('payable_amount', 12)->default(0)->comment('买家应付金额');
                $table->decimal('payment_amount', 12)->default(0)->comment('买家实付金额');
                $table->decimal('refund_amount', 12)->default(0)->comment('买家退款金额');

                // 统计类
                $table->decimal('cost_price', 12)->default(0)->comment('成本单价');
                $table->decimal('total_cost_price', 12)->default(0)->comment('成本总价');

                // 退款售后
                $table->enum('refund_status', RefundStatusEnum::values())->comment(RefundStatusEnum::comments('退款状态'));
                $table->enum('phase', RefundPhaseEnum::values())->comment(RefundPhaseEnum::comments('阶段'));
                $table->enum('refund_type', RefundTypeEnum::values())->comment(RefundTypeEnum::comments('售后类型'));
                $table->boolean('has_good_return')->default(false)->comment('是否需要退货');
                $table->enum('good_status', RefundGoodsStatusEnum::values())->nullable()->comment(RefundGoodsStatusEnum::comments('货物状态'));
                $table->string('reason')->nullable()->comment('原因');
                $table->decimal('refund_freight_amount', 12)->default(0)->comment('退运费');
                $table->decimal('refund_product_amount', 12)->default(0)->comment('退商品金额');
                $table->decimal('total_refund_amount', 12)->default(0)->comment('总退款金额'); // 退商品金额 + 邮费


                $table->string('outer_refund_id', 64)->nullable()->comment('外部退款单号');

                //  TODO 供应商相关

                $table->timestamp('created_time')->nullable()->comment('创建时间');
                $table->timestamp('end_time')->nullable()->comment('完结时间');
                $table->string('seller_custom_status')->nullable()->comment('卖家自定义状态');
                $table->unsignedTinyInteger('star')->nullable()->comment('加星');
                $table->unsignedTinyInteger('urge')->nullable()->comment('催单');
                $table->timestamp('urge_time')->nullable()->comment('催单时间');
                $table->operator();
                $table->softDeletes();
                $table->comment('订单-退款表');

                $table->index(['order_no', 'order_product_no'], 'idx_order_product');
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
