<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_markets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id')->comment('仓库ID');

            // ========== 关联到市场/门店 ==========
            $table->string('market', 64)->comment('市场：cn, us, de, *（*表示所有市场）');
            $table->string('store', 64)->default('*')->comment('门店：default-默认门店，store_xxx-具体门店，* 表示所有门店');

            // ========== 状态 ==========
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->boolean('is_primary')->default(false)->comment('是否主要市场/门店');


            $table->operator();
            $table->softDeletes();

            // ========== 索引 ==========
            $table->unique(['warehouse_id', 'market', 'store'], 'uk_warehouse_market_store');
            $table->index('warehouse_id');
            $table->index(['market', 'store']);

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

            $table->comment('仓库-市场/门店关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_markets');
    }
};

