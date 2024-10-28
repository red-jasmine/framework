<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_refunds', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('售后单号');
            $table->unsignedBigInteger('order_id')->comment('订单号');
            $table->unsignedBigInteger('order_product_id')->comment('订单商品单号');

            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家类型');
            $table->string('order_product_type', 32)->comment(ProductTypeEnum::comments('订单商品类型'));
            $table->string('shipping_type', 32)->comment(ShippingTypeEnum::comments('发货类型'));


            $table->string('title')->comment('商品标题');
            $table->string('sku_name')->nullable()->comment('规格名称');
            $table->string('image')->nullable()->comment('图片');
            $table->string('product_type', 32)->comment('商品源类型');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('规格ID');
            $table->unsignedBigInteger('category_id')->default(0)->comment('类目ID');
            $table->unsignedBigInteger('product_group_id')->default(0)->comment('商品分组ID');
            $table->string('outer_id', 64)->nullable()->comment('商品外部编码');
            $table->string('outer_sku_id', 64)->nullable()->comment('SKU外部编码');
            $table->string('barcode', 64)->nullable()->comment('条形码');
            $table->unsignedBigInteger('unit_quantity')->default(1)->comment('单位数量');
            $table->string('unit')->nullable()->comment('单位');
            $table->unsignedBigInteger('num')->default(0)->comment('数量');
            $table->decimal('price', 12)->default(0)->comment('价格');
            $table->decimal('cost_price', 12)->default(0)->comment('成本价格');
            $table->decimal('product_amount', 12)->default(0)->comment('商品金额');
            $table->decimal('tax_amount', 12)->default(0)->comment('税费');
            $table->decimal('discount_amount', 12)->default(0)->comment('商品优惠');
            $table->decimal('payable_amount', 12)->default(0)->comment('应付金额');
            $table->decimal('payment_amount', 12)->default(0)->comment('实付金额');
            $table->decimal('divided_payment_amount', 12)->default(0)->comment('分摊后实际付款金额');
            $table->string('shipping_status', 32)->nullable()->comment(ShippingStatusEnum::comments('发货状态'));

            $table->string('refund_type', 32)->comment(RefundTypeEnum::comments('售后类型'));
            $table->string('phase', 32)->comment(RefundPhaseEnum::comments('阶段'));
            $table->unsignedTinyInteger('has_good_return')->default(0)->comment('是否需要退货');

            $table->string('good_status', 32)->nullable()->comment(RefundGoodsStatusEnum::comments('货物状态'));
            $table->string('reason')->nullable()->comment('原因');
            $table->string('outer_refund_id', 64)->nullable()->comment('外部退款单号');

            $table->string('refund_status', 32)->comment(RefundStatusEnum::comments('退款状态'));
            $table->decimal('freight_amount', 12)->default(0)->comment('运费');
            $table->decimal('refund_amount', 12)->default(0)->comment('退款金额');
            $table->decimal('total_refund_amount', 12)->default(0)->comment('总退款金额'); // 退商品金额 + 邮费
            $table->timestamp('created_time')->nullable()->comment('创建时间');
            $table->timestamp('end_time')->nullable()->comment('完结时间');
            $table->string('seller_custom_status', 30)->nullable()->comment('卖家自定义状态');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator'); // 创建人
            $table->nullableMorphs('updater'); // 更新人
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-退款表');

            $table->index([ 'order_id', 'order_product_id' ], 'idx_order_product');
            $table->index([ 'seller_id', 'seller_type', 'refund_status' ], 'idx_seller');
            $table->index([ 'buyer_id', 'buyer_type', 'refund_status' ], 'idx_buyer');
            $table->comment('订单-商品表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_refunds');
    }
};
