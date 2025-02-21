<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( config('red-jasmine-product.tables.prefix','jasmine_') .'product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type',64);
            $table->string('owner_id',64);
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->string('action_type', 32)->comment(ProductStockActionTypeEnum::comments('操作类型'));
            $table->bigInteger('action_stock')->comment('操作库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->string('change_type', 32)->comment(ProductStockChangeTypeEnum::comments('变更类型'));
            $table->string('change_detail')->nullable()->comment('变更明细');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->comment('商品-库存-记录');
            $table->index([ 'product_id', ], 'idx_product');
            $table->index([ 'sku_id', ], 'idx_sku');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix','jasmine_') . 'product_stock_logs');
    }
};
