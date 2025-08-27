<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Promotion\Domain\Models\Enums\SkuStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('promotion_activity_skus', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('activity_id')->comment('活动ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->unsignedBigInteger('activity_product_id')->comment('活动商品ID');

            // SKU信息
            $table->string('properties_name', 255)->nullable()->comment('规格名称');
            $table->string('image', 500)->nullable()->comment('SKU主图');
            $table->decimal('original_price', 10, 2)->comment('原价');

            // 活动设置 (独立设置模式)
            $table->decimal('activity_price', 10, 2)->nullable()->comment('活动价');
            $table->decimal('discount_rate', 5, 2)->nullable()->comment('折扣率');
            $table->integer('activity_stock')->nullable()->comment('活动库存');
            $table->integer('locked_stock')->default(0)->comment('已锁定库存');
            $table->integer('user_purchase_limit')->nullable()->comment('单用户限购数量');

            // 状态
            $table->enum('status', SkuStatusEnum::values())->default(SkuStatusEnum::ACTIVE)->comment(SkuStatusEnum::comments('状态'));
            $table->boolean('is_show')->default(true)->comment('是否展示');

            // 数据统计
            $table->unsignedBigInteger('views')->default(0)->comment('浏览量');
            $table->unsignedBigInteger('sales')->default(0)->comment('销售数量');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('销售金额');
            // 操作信息
            $table->operator();
            $table->softDeletes();

            // 索引
            $table->unique(['activity_id', 'sku_id'], 'uk_activity_sku');
            $table->index(['activity_id'], 'idx_activity');
            $table->index(['product_id'], 'idx_product');
            $table->index(['sku_id'], 'idx_sku');
            $table->index(['activity_product_id'], 'idx_activity_product');
            $table->index(['creator_type', 'creator_id'], 'idx_creator');
            $table->index(['activity_id', 'product_id'], 'idx_skus_activity_product');
            $table->comment('促销活动SKU表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('promotion_activity_skus');
    }
};
