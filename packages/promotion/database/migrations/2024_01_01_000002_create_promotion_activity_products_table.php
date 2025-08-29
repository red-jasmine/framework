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
            $table->string('image')->nullable()->comment('商品主图');
            $table->decimal('original_price', 10, 2)->comment('原价');
            $table->bigInteger('sort')->default(0)->comment('排序');

            // 价格
            $table->decimal('activity_price', 10, 2)->nullable()->comment('统一活动价');

            //
            $table->decimal('discount_rate', 5, 2)->nullable()->comment('统一折扣率');
            $table->boolean('is_unified_stock')->default(false)->comment('是否统一库存');

            $table->bigInteger('activity_stock')->default(0)->comment('活动库存');
            $table->bigInteger('stock')->default(0)->comment('可用库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');

            // 参与限制
            $table->integer('user_purchase_limit')->nullable()->comment('单用户限购数量');


            // 时间设置
            $table->datetime('start_time')->nullable()->comment('商品参与开始时间');
            $table->datetime('end_time')->nullable()->comment('商品参与结束时间');

            // 状态
            $table->string('status', ActivityProductStatusEnum::values())
                  ->default(ActivityProductStatusEnum::PENDING)->comment(ActivityProductStatusEnum::comments('商品状态'));
            $table->boolean('is_show')->default(true)->comment('是否展示');

            // 数据统计


            $table->unsignedBigInteger('views')->default(0)->comment('浏览量');
            $table->unsignedBigInteger('sales')->default(0)->comment('销售数量');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('销售金额');

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
