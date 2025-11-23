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

            $table->string('market', 64)->default('default')->comment('市场');
            // 注意：业务线（biz）是商家属性，不是商品属性，通过 owner 关联商家获取
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);

            $table->string('product_type', 32)->comment(ProductTypeEnum::comments('商品类型'));
            $table->string('status', 32)->comment(ProductStatusEnum::comments('状态'));
            // 基础信息
            $table->string('title')->comment('标题');
            $table->string('slogan')->nullable()->comment('广告语');
            $table->string('image')->nullable()->comment('主图');

            $table->boolean('is_brand_new')->default(true)->comment('是否全新');
            $table->boolean('is_alone_order')->default(false)->comment('是否单独下单');
            $table->boolean('is_pre_sale')->default(false)->comment('是否预售');
            $table->boolean('is_customized')->default(false)->comment('是否定制');
            $table->boolean('has_variants')->default(false)->comment('多规格');


            // 类目信息
            $table->unsignedBigInteger('category_id')->nullable()->comment('类目ID');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('品牌ID');
            $table->string('model_code', 64)->nullable()->comment('型号编码');
            $table->string('spu')->nullable()->comment('商品编码');
            // 类目标准商品ID
            $table->unsignedBigInteger('standard_product_id')->nullable()->comment('类目标品ID');
            // 商家分组
            $table->unsignedBigInteger('product_group_id')->nullable()->comment('商品分组');

            // 支持的履约方式
            $table->string('shipping_types')->nullable()->comment(ShippingTypeEnum::comments('发货方式'));

            // 限购设置
            $table->unsignedTinyInteger('vip')->default(0)->comment('VIP');
            $table->string('order_quantity_limit_type')->default(OrderQuantityLimitTypeEnum::UNLIMITED)->comment(OrderQuantityLimitTypeEnum::comments('下单数量限制类型'));
            $table->unsignedBigInteger('order_quantity_limit_num')->default(0)->nullable()->comment('下单数量限制数量');

            // 商品统一价格货币
            $table->string('currency', 3)->default('CNY')->comment('货币');

            // 商品最低价
            $table->decimal('price', 12)->default(0)->comment('销售价');
            $table->decimal('market_price', 12)->nullable()->comment('市场价');
            $table->decimal('cost_price', 12)->nullable()->comment('成本价');

            // 税率 修改


            // 库存
            $table->string('sub_stock', 32)->comment(SubStockTypeEnum::comments('减库存方式'));
            // 商品级别库存制作统计使用
            $table->bigInteger('stock')->default(0)->comment('总库存');
            $table->bigInteger('available_stock')->default(0)->comment('总可用库存');
            $table->bigInteger('locked_stock')->default(0)->comment('总锁定库存');
            $table->bigInteger('reserved_stock')->default(0)->comment('总预留库存');


            // 发货期限
            $table->unsignedInteger('delivery_time')->default(0)->comment('发货时间');

            // 运营类
            $table->unsignedInteger('gift_point')->default(0)->comment('积分');
            // 数量范围
            $table->unsignedBigInteger('min_limit')->default(1)->comment('起售量');
            $table->unsignedBigInteger('max_limit')->default(0)->comment('限购量');
            $table->unsignedBigInteger('step_limit')->default(1)->comment('数量倍数');

            // ============ 国际化元数据 ============

            $table->boolean('taxable')->default(false)->comment('交税');
            $table->string('tax_code', 64)->nullable()->comment('税码');
            // （ISO 3166-1 alpha-2)
            $table->string('origin_country', 2)->nullable()->comment('原产国');
            $table->string('hs_code', 64)->nullable()->comment('海关编码');

            // 运营类
            $table->boolean('is_hot')->default(false)->comment('热销');
            $table->boolean('is_new')->default(false)->comment('新品');
            $table->boolean('is_best')->default(false)->comment('精品');
            $table->boolean('is_benefit')->default(false)->comment('特惠');
            $table->bigInteger('sort')->default(0)->comment('排序');

            // 统计项
            $table->unsignedBigInteger('sales')->default(0)->comment('销售量');
            $table->unsignedBigInteger('views')->default(0)->comment('浏览量');
            $table->unsignedBigInteger('likes')->default(0)->comment('喜欢量');
            $table->unsignedBigInteger('favorites')->default(0)->comment('收藏量');

            // 时间
            $table->timestamp('available_at')->nullable()->comment('上架时间');
            $table->timestamp('paused_at')->nullable()->comment('暂停销售时间');
            $table->timestamp('unavailable_at')->nullable()->comment('下架时间');
            // 操作
            $table->timestamp('modified_at')->nullable()->comment('修改时间');

            $table->operator();
            $table->softDeletes();
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
