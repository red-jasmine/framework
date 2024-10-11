<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('SKU ID');
            $table->morphs('owner');
            $table->unsignedBigInteger('product_id')->default(0)->comment('商品ID');
            $table->string('properties_name')->nullable()->comment('规格名称');
            $table->string('properties_sequence')->nullable()->comment('规格属性序列');
            // SKU 信息
            $table->decimal('price', 10)->default(0)->comment('销售价');
            $table->decimal('market_price', 10)->nullable()->comment('市场价');
            $table->decimal('cost_price', 10)->nullable()->comment('成本价');

            // 库存
            $table->bigInteger('stock')->default(0)->comment('库存');
            $table->bigInteger('channel_stock')->default(0)->comment('渠道库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->unsignedBigInteger('safety_stock')->default(0)->comment('安全库存');
            // 信息
            $table->string('image')->nullable()->comment('主图');

            $table->string('outer_id')->nullable()->comment('规格编码');
            $table->string('barcode', 32)->nullable()->comment('规格条码');
            $table->string('weight')->nullable()->comment('重量:kg');
            $table->string('width')->nullable()->comment('宽度:m');
            $table->string('height')->nullable()->comment('高度:m');
            $table->string('length')->nullable()->comment('长度:m');
            $table->string('size')->nullable()->comment('体积:m³');
            // 状态
            $table->string('status',32)->comment('状态');
            // 销量
            $table->unsignedBigInteger('sales')->default(0)->comment('销量');
            // 供应商
            $table->unsignedBigInteger('supplier_sku_id')->nullable()->comment('供应商 SKU ID');
            // 操作
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->timestamp('modified_time')->nullable()->comment('修改时间');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-SKU表');
            $table->index([ 'product_id', ], 'idx_product');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') .'product_skus');
    }
};
