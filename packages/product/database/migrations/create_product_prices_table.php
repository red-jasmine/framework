<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {


        Schema::create('product_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('汇总价格ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');

            // ========== 价格维度（支持通配符 *） ==========
            $table->string('market', 64)->default('*')->comment('市场：cn, us, de, *');
            $table->string('store', 64)->default('*')->comment('门店：default-默认门店，store_xxx-具体门店，*-全部门店');
            $table->string('user_level', 32)->default('*')->comment('用户等级：default-普通, vip-VIP, gold-黄金会员, platinum-白金会员, *');
            $table->unsignedBigInteger('quantity')->default(1)->comment('数量');

            // ========== 价格信息 ==========
            $table->char('currency', 3)->comment('货币：CNY, USD, EUR');
            $table->decimal('price', 12)->nullable()->comment('最低价');
            $table->decimal('market_price', 12)->nullable()->comment('市场价');
            $table->decimal('cost_price', 12)->nullable()->comment('成本价');

            $table->operator();
            $table->softDeletes();


            // 索引
            $table->unique(['product_id', 'market', 'store', 'user_level','quantity'], 'idx_product_price_unique');
            $table->index(['product_id', 'market', 'store', 'user_level'], 'idx_product_price_dimensions');
            $table->index(['market', 'store', 'user_level'], 'idx_price_dimensions');

            $table->comment('商品级别价格汇总表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};

