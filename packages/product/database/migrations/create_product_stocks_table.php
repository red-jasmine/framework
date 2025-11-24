<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('variant_id')->comment('SKU ID（变体ID，必填）');
            // 商品所属者
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            // ========== 库存维度（关联仓库）==========
            $table->unsignedBigInteger('warehouse_id')->default(0)->comment('仓库ID（关联warehouses表，0表示总仓/默认仓库/简单模式）');

            // ========== 库存数量 ==========
            $table->bigInteger('stock')->default(0)->comment('总库存');
            $table->bigInteger('available_stock')->default(0)->comment('可用库存');
            $table->bigInteger('locked_stock')->default(0)->comment('锁定库存');
            $table->bigInteger('reserved_stock')->default(0)->comment('预留库存');


            // ========== 库存状态 ==========
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->unsignedInteger('priority')->default(0)->comment('优先级');
            $table->bigInteger('safety_stock')->default(0)->comment('安全库存');

            // ========== 操作信息 ==========
            $table->operator();
            $table->softDeletes();

            // ========== 索引 ==========
            // 注意：warehouse_id = 0 表示总仓（默认仓库），用于简单库存模式
            // 唯一索引 uk_variant_warehouse 自动保证每个变体只有一条 warehouse_id=0 的记录
            $table->unique(['variant_id', 'warehouse_id'], 'uk_variant_warehouse');
            $table->index('warehouse_id', 'idx_warehouse');
            $table->index(['product_id', 'variant_id'], 'idx_product_variant');

            $table->comment('商品-多市场库存表（变体级别）');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stocks');
    }
};
