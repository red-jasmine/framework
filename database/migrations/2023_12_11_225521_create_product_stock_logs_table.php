<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('owner');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->string('type', 32)->comment(ProductStockTypeEnum::comments('操作类型'));
            $table->string('change_type', 32)->comment(ProductStockChangeTypeEnum::comments('变更类型'));
            $table->string('change_detail')->nullable()->comment('变更明细');
            $table->bigInteger('stock')->comment('库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->nullableMorphs('channel');
            $table->nullableMorphs('creator');
            $table->timestamps();
            $table->comment('商品-库存-记录');
            $table->index([ 'product_id', ], 'idx_product');
            $table->index([ 'owner_id', 'owner_type', ], 'idx_owner');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stock_logs');
    }
};
