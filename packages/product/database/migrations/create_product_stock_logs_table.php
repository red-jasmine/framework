<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            
            // ========== 仓库信息（多仓库支持）==========
            $table->unsignedBigInteger('warehouse_id')->default(0)->comment('仓库ID（0表示总仓/默认仓库/简单模式）');
            
            $table->string('action_type', 32)->comment(ProductStockActionTypeEnum::comments('操作类型'));
            $table->bigInteger('action_stock')->comment('操作库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->bigInteger('before_stock')->comment('操作前库存');
            $table->bigInteger('after_stock')->comment('操作后库存');
            $table->bigInteger('before_lock_stock')->comment('操作前锁定库存');
            $table->bigInteger('after_lock_stock')->comment('操作后锁定库存');
            $table->string('change_type', 32)->comment(ProductStockChangeTypeEnum::comments('变更类型'));
            $table->string('change_detail', 64)->nullable()->comment('变更明细');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->comment('商品-库存-记录');
            $table->index(['product_id',], 'idx_product');
            $table->index(['sku_id',], 'idx_sku');
            $table->index(['warehouse_id',], 'idx_warehouse');
            $table->index(['warehouse_id', 'sku_id'], 'idx_warehouse_sku');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stock_logs');
    }
};
