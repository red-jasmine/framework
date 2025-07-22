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
             
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('order_no', 64)->unique()->comment('兑换订单号');
            $table->string('outer_order_no')->comment('关联订单ID');
            $table->unsignedBigInteger('point_product_id')->comment('积分商品ID');
            $table->string('product_title')->comment('商品标题');
            $table->integer('point')->default(0)->comment('积分');
            $table->string('price_currency', 3)->default('CNY')->comment('价格货币');
            $table->decimal('price_amount', 10, 2)->default(0.00)->comment('价格金额');
            $table->integer('quantity')->default(1)->comment('数量');
            $table->string('payment_mode')->comment('支付模式');
            $table->string('payment_status')->comment('支付状态');
            $table->string('status')->default(PointsExchangeOrderStatusEnum::EXCHANGED->value)->comment('状态');
            $table->timestamp('exchange_time')->comment('兑换时间');
            $table->json('shipping_info')->nullable()->comment('物流信息');
            $table->json('address_info')->nullable()->comment('地址信息');
            $table->json('payment_info')->nullable()->comment('支付信息');
           
            
            $table->operator();
            $table->softDeletes();
            $table->comment('积分兑换订单表');
            
            // 索引
            $table->index('order_id', 'idx_order_id');
            $table->index('product_id', 'idx_product_id');
            $table->index('status', 'idx_status');
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('points_exchange_orders');
    }
}; 