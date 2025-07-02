<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('shopping_cart_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id')->comment('购物车ID');
            $table->userMorphs('seller', '卖家', false);
            $table->string('product_type', 32)->comment('商品类型');
            $table->string('product_id', 64)->comment('商品ID');
            $table->string('sku_id', 64)->comment('SKU ID');
            $table->integer('quantity')->default(1)->comment('数量');
            // 商品信息
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->string('price_currency', 3)->default('CNY')->comment('货币');
            $table->decimal('price_amount', 10)->default(0)->comment('销售价');
            $table->string('title')->nullable()->comment('商品标题');
            $table->string('properties_name')->nullable()->comment('SKU 属性名称');
            $table->string('image')->nullable()->comment('商品主图');
            $table->json('customized')->nullable()->comment('定制信息');
            $table->json('extra')->nullable()->comment('扩展信息');
            $table->operator();
            $table->comment('购物车商品表');
            $table->index(['cart_id']);

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('shopping_cart_products');
    }
}; 