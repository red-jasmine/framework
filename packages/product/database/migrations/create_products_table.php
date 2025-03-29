<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->primary()->comment('ID');

            $table->string('app_id', 64);
            // 卖家信息
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);

            $table->string('title')->comment('标题');
            $table->string('product_type', 32)->comment(ProductTypeEnum::comments('商品类型'));
            $table->string('shipping_type', 32)->comment(ShippingTypeEnum::comments('发货类型'));
            $table->string('status', 32)->comment(ProductStatusEnum::comments('状态'));
            $table->boolean('is_brand_new')->default(true)->comment('是否全新');
            $table->boolean('is_alone_order')->default(false)->comment('是否单独下单');
            $table->boolean('is_pre_sale')->default(false)->comment('是否预售');

            // 基础信息
            $table->string('image')->nullable()->comment('主图');
            $table->boolean('is_customized')->default(false)->comment('是否定制');
            $table->boolean('is_multiple_spec')->default(false)->comment('是否为多规格');
            $table->string('slogan')->nullable()->comment('广告语');
            // 类目信息
            $table->string('product_model')->nullable()->comment('产品型号');
            $table->unsignedTinyInteger('spu_id')->nullable()->comment('标品ID');
            $table->unsignedBigInteger('brand_id')->default(0)->comment('品牌ID');
            $table->unsignedBigInteger('category_id')->default(0)->comment('类目ID');
            $table->unsignedBigInteger('product_group_id')->default(0)->comment('商品分组');
            // 运费
            $table->string('freight_payer', 32)->comment(FreightPayerEnum::comments('运费承担方'));
            $table->unsignedBigInteger('postage_id')->nullable()->comment('运费模板ID');
            $table->string('sub_stock', 32)->comment(SubStockTypeEnum::comments('减库存方式'));
            // 限购设置
            $table->unsignedTinyInteger('vip')->default(0)->comment('VIP');
            $table->string('order_quantity_limit_type')->default(OrderQuantityLimitTypeEnum::UNLIMITED)->comment(OrderQuantityLimitTypeEnum::comments('下单数量限制类型'));
            $table->unsignedBigInteger('order_quantity_limit_num')->default(0)->nullable()->comment('下单数量限制数量');

            // 价格
            $table->string('currency', 10)->default('CNY')->comment('货币');
            $table->decimal('price', 12)->default(0)->comment('销售价');
            $table->decimal('market_price', 12)->nullable()->comment('市场价');
            $table->decimal('cost_price', 12)->nullable()->comment('成本价');

            $table->bigInteger('stock')->default(0)->comment('库存');
            $table->bigInteger('channel_stock')->default(0)->comment('渠道库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->unsignedBigInteger('safety_stock')->default(0)->comment('安全库存');

            $table->string('outer_id')->nullable()->comment('商品编码');
            $table->string('barcode', 32)->nullable()->comment('商品条码');
            // 运营类
            $table->unsignedInteger('gift_point')->default(0)->comment('积分');
            $table->string('unit')->nullable()->comment('单位');
            $table->unsignedBigInteger('unit_quantity')->default(1)->comment('单位数量');
            $table->unsignedInteger('delivery_time')->default(0)->comment('发货时间');
            // 数量范围
            $table->unsignedBigInteger('min_limit')->default(1)->comment('起售量');
            $table->unsignedBigInteger('max_limit')->default(0)->comment('限购量');
            $table->unsignedBigInteger('step_limit')->default(1)->comment('数量步长');

            // 供应商
            $table->boolean('is_from_supplier')->default(false)->comment('是否来自供应商');
            $table->string('supplier_type')->nullable()->comment('供应商类型');
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('供应商ID');
            $table->unsignedBigInteger('supplier_product_id')->nullable()->comment('供应商 商品ID');

            // 运营类
            $table->boolean('is_hot')->default(false)->comment('热销');
            $table->boolean('is_new')->default(false)->comment('新品');
            $table->boolean('is_best')->default(false)->comment('精品');
            $table->boolean('is_benefit')->default(false)->comment('特惠');
            $table->bigInteger('sort')->default(0)->comment('排序');


            $table->timestamp('start_sale_time')->nullable()->comment('定时上架时间');
            $table->timestamp('end_sale_time')->nullable()->comment('定时下架时间');
            // 统计项
            $table->unsignedBigInteger('sales')->default(0)->comment('销售量');
            $table->unsignedBigInteger('views')->default(0)->comment('浏览量');
            $table->unsignedBigInteger('likes')->default(0)->comment('喜欢量');
            $table->unsignedBigInteger('favorites')->default(0)->comment('收藏量');

            // 时间
            $table->timestamp('on_sale_time')->nullable()->comment('上架时间');
            $table->timestamp('sold_out_time')->nullable()->comment('售停时间');
            $table->timestamp('stop_sale_time')->nullable()->comment('下架时间');
            // 操作
            $table->timestamp('modified_time')->nullable()->comment('修改时间');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 税率
            // 审核状态
            // 是否违规

            $table->comment('商品表');


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
