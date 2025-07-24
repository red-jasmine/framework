<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('points_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('title')->nullable()->comment('商品标题');
            $table->text('description')->nullable()->comment('商品描述');
            $table->string('image')->nullable()->comment('商品图片');
            $table->integer('point')->default(0)->comment('积分价格');
            $table->string('price_currency', 3)->default('CNY')->comment('价格货币');
            $table->decimal('price_amount', 10)->default(0.00)->comment('价格金额');
            $table->string('payment_mode')->default(PointsProductPaymentModeEnum::POINTS->value)->comment('支付模式');
            $table->bigInteger('stock')->default(0)->comment('库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->unsignedBigInteger('safety_stock')->default(0)->comment('安全库存');
            $table->integer('exchange_limit')->default(0)->comment('兑换限制');
            $table->string('status')->default(PointsProductStatusEnum::ON_SALE->value)->comment('状态');
            $table->integer('sort')->default(0)->comment('排序');
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->string('product_type')->comment('商品源类型');
            $table->string('product_id')->comment('商品源ID');


            $table->operator();
            $table->softDeletes();
            $table->comment('积分商品表');

            // 索引
            $table->index(['status', 'sort'], 'idx_status_sort');
            $table->index('category_id', 'idx_category_id');
            $table->index('payment_mode', 'idx_payment_mode');
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('points_products');
    }
}; 