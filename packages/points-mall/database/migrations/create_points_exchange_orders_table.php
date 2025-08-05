<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsExchangeOrderStatusEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('points_exchange_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('points_order_no', 64)->unique()->comment('积分订单号');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->userMorphs('user', '用户', false);
            $table->string('outer_order_no')->comment('关联订单号');
            $table->string('title')->nullable()->comment('标题');
            $table->string('image')->nullable()->comment('图片');
            $table->unsignedBigInteger('point_product_id')->comment('积分商品ID');
            $table->string('product_type')->comment('商品源类型');
            $table->string('product_id')->comment('商品源ID');
            $table->string('sku_id')->comment('商品源规格ID');
            $table->bigInteger('point')->default(0)->comment('积分');
            $table->string('price_currency', 3)->default('CNY')->comment('价格货币');
            $table->decimal('price_amount', 10)->default(0.00)->comment('价格金额');
            $table->integer('quantity')->default(1)->comment('数量');
            $table->bigInteger('total_point')->default(0)->comment('积分');
            $table->string('total_amount_currency', 3)->default('CNY')->comment('价格货币');
            $table->decimal('total_amount_amount', 10)->default(0.00)->comment('价格金额');

            // 订单状态


            $table->enum('status', PointsExchangeOrderStatusEnum::values())->default(PointsExchangeOrderStatusEnum::PAYING)
                  ->comment(PointsExchangeOrderStatusEnum::comments('状态'));
            $table->timestamp('exchange_time')->comment('兑换时间');


            $table->operator();
            $table->softDeletes();
            $table->comment('积分兑换订单表');

            // 索引
            $table->index('points_order_no', 'idx_points_order_no');
            $table->index('product_id', 'idx_product_id');
            $table->index('status', 'idx_status');
            $table->index(['owner_id', 'owner_type'], 'idx_owner');
            $table->index(['user_id', 'user_type'], 'idx_user');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('points_exchange_orders');
    }
};