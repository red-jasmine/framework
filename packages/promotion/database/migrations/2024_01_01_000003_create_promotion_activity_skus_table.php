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
            $table->userMorphs('seller', '卖家', false);
            $table->string('product_type')->comment('商品源类型');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');


            // SKU信息
            $table->string('title', 255)->nullable()->comment('规格名称');
            $table->string('image', 500)->nullable()->comment('主图');

            // 活动价格
            $table->string('original_price_currency', 3)->default('CNY')->comment('货币');
            $table->decimal('original_price_amount', 12)->comment('原价');
            $table->string('activity_price_currency', 3)->default('CNY')->comment('货币');
            $table->decimal('activity_price_amount', 12)->default(0)->comment('销售价');
            // 活动设置 (独立设置模式)


            $table->bigInteger('activity_stock')->default(0)->comment('活动库存');
            $table->bigInteger('stock')->default(0)->comment('可用库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');

            $table->bigInteger('user_purchase_limit')->nullable()->comment('单用户限购数量');

            // 状态
            $table->enum('status', SkuStatusEnum::values())->default(SkuStatusEnum::ACTIVE)->comment(SkuStatusEnum::comments('状态'));
            $table->boolean('is_show')->default(true)->comment('是否展示');

            // 数据统计
            $table->unsignedBigInteger('total_users')->default(0)->comment('总参与用户数');
            $table->unsignedBigInteger('total_orders')->default(0)->comment('总订单数');
            $table->unsignedBigInteger('total_sales')->default(0)->comment('总销量');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('总销售金额');
            // 操作信息
            $table->operator();
            $table->softDeletes();

            // 索引

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
