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
            $table->string('shop_type', 32)->comment('店铺类型');
            $table->string('shop_id', 64)->comment('店铺ID');
            $table->string('product_type', 32)->comment('商品类型');
            $table->string('product_id', 64)->comment('商品ID');
            $table->string('sku_id', 64)->comment('SKU ID');
            $table->integer('quantity')->default(1)->comment('数量');
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->decimal('price', 12)->default(0)->comment('销售单价');
            $table->decimal('discount_amount', 12)->default(0)->comment('优惠金额');
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