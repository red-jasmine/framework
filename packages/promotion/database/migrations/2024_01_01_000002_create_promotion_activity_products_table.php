<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityProductStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('promotion_activity_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('activity_id')->comment('活动ID');
            $table->string('seller_type', 64)->comment('卖家类型');
            $table->string('seller_id', 64)->comment('卖家ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');


            // 商品信息
            $table->string('title')->comment('商品标题');
            $table->string('image', 500)->nullable()->comment('商品主图');
            $table->decimal('original_price', 10, 2)->comment('原价');


            // 活动设置 (统一设置模式)
            $table->decimal('activity_price', 10, 2)->nullable()->comment('统一活动价');
            $table->decimal('discount_rate', 5, 2)->nullable()->comment('统一折扣率');
            $table->integer('activity_stock')->nullable()->comment('统一活动库存');
            $table->integer('locked_stock')->default(0)->comment('已锁定库存');
            $table->integer('user_purchase_limit')->nullable()->comment('单用户限购数量');

            // 参与模式
            $table->string('sku_participation_mode', 32)->default('all_skus')->comment('SKU参与模式 (all_skus, specific_skus)');
            $table->string('price_setting_mode', 32)->default('unified')->comment('价格设置模式 (unified, individual)');
            $table->string('stock_management_mode', 32)->default('unified')->comment('库存管理模式 (unified, individual)');

            // 时间设置
            $table->datetime('start_time')->nullable()->comment('商品参与开始时间');
            $table->datetime('end_time')->nullable()->comment('商品参与结束时间');

            // 状态
            $table->string('status', ActivityProductStatusEnum::values())
                  ->default(ActivityProductStatusEnum::PENDING)->comment(ActivityProductStatusEnum::comments('商品状态'));
            $table->boolean('is_show')->default(true)->comment('是否展示');

            // 数据统计
            $table->unsignedBigInteger('sales')->default(0)->comment('商品销量');
            $table->integer('activity_sales_volume')->default(0)->comment('活动销量');
            $table->decimal('activity_sales_amount', 12, 2)->default(0.00)->comment('活动销售金额');

            // 操作信息
            $table->operator();
            $table->softDeletes();

            // 索引
            $table->unique(['activity_id', 'product_id'], 'uk_activity_product');
            $table->index(['activity_id'], 'idx_activity');
            $table->index(['product_id'], 'idx_product');
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index(['seller_type', 'seller_id'], 'idx_seller');
            $table->index(['creator_type', 'creator_id'], 'idx_creator');
            $table->index(['status'], 'idx_status');
            $table->index(['activity_id', 'status'], 'idx_products_activity_status');
            $table->comment('促销活动商品表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('promotion_activity_products');
    }
};
