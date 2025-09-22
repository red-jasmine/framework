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
            $table->boolean('selected')->default(false)->comment('是否选中');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_cart_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('cart_id')->comment('购物车ID');
            $table->string('shop_type')->comment('店铺类型');
            $table->unsignedBigInteger('shop_id')->comment('店铺ID');
            $table->string('product_type')->comment('商品类型');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->string('title')->comment('商品标题');
            $table->string('image')->nullable()->comment('商品图片');
            $table->text('properties_name')->nullable()->comment('规格属性名称');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->decimal('price', 10, 2)->default(0)->comment('单价');
            $table->decimal('original_price', 10, 2)->default(0)->comment('原价');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('折扣金额');
            $table->boolean('selected')->default(true)->comment('是否选中');
            $table->json('extra')->nullable()->comment('扩展信息');
            $table->json('customized')->nullable()->comment('定制信息');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['cart_id']);
            $table->index(['shop_type', 'shop_id']);
            $table->index(['product_type', 'product_id']);
            $table->index(['sku_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_cart_products');
    }
};
