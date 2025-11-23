<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('SKU ID');
            $table->unsignedBigInteger('product_id')->default(0)->comment('商品ID');


            $table->string('market', 64)->default('default')->comment('市场');
            // 注意：业务线（biz）是商家属性，不是商品属性，通过 owner 关联商家获取
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);

            $table->string('attrs_name')->nullable()->comment('属性名称');
            $table->string('attrs_sequence')->nullable()->comment('属性序列');

            // 状态
            $table->string('status', 32)->comment('状态');

            // 价格
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->decimal('price', 12)->default(0)->comment('销售价');
            $table->decimal('market_price', 12)->nullable()->comment('市场价');
            $table->decimal('cost_price', 12)->nullable()->comment('成本价');

            // 库存（汇总数据，从 product_stocks 汇总而来，仅用于统计展示）
            $table->bigInteger('stock')->default(0)->comment('总库存');
            $table->bigInteger('available_stock')->default(0)->comment('总可用库存');
            $table->bigInteger('locked_stock')->default(0)->comment('总锁定库存');
            $table->bigInteger('reserved_stock')->default(0)->comment('总预留库存');

            // 信息
            $table->string('image')->nullable()->comment('主图');
            $table->string('sku')->nullable()->comment('SKU编码');
            $table->string('gtin', 64)->nullable()->comment('国际条码');
            $table->string('barcode', 64)->nullable()->comment('自定义条码');


            $table->decimal('weight', 10, 3)->nullable()->comment('重量');
            $table->string('weight_unit', 10)->default('kg')->comment('重量单位');
            $table->decimal('length')->nullable()->comment('长度');
            $table->decimal('width')->nullable()->comment('宽度');
            $table->decimal('height')->nullable()->comment('高度');
            $table->string('dimension_unit', 10)->default('m')->comment('尺寸单位');
            $table->decimal('volume')->nullable()->comment('体积');

            // 销量
            $table->unsignedBigInteger('sales')->default(0)->comment('销量');

            // 包装单位
            $table->unsignedBigInteger('package_quantity')->default(1)->comment('包内数量');
            $table->string('package_unit')->nullable()->comment('包装单位');
            // 操作

            $table->timestamp('modified_at')->nullable()->comment('修改时间');
            $table->operator();
            $table->softDeletes();
            $table->comment('商品-SKU表');
            $table->index(['product_id',], 'idx_product');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_variants');
    }
};
