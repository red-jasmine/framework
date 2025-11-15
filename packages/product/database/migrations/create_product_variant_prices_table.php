<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variant_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('价格ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('variant_id')->comment('SKU ID');

            // ========== 价格维度（支持通配符 *） ==========
            $table->string('market', 64)->default('*')->comment('市场：cn, us, de, *');
            $table->string('store', 64)->default('*')->comment('门店：default-默认门店，store_xxx-具体门店，*-全部门店');
            $table->string('user_level', 32)->default('*')->comment('用户等级：default-普通, vip-VIP, gold-黄金会员, platinum-白金会员, *');

            // ========== 价格信息 ==========
            $table->char('currency', 3)->comment('货币：CNY, USD, EUR');
            $table->decimal('price', 12, 2)->comment('销售价（根据 user_level 不同而不同，如普通价、VIP价、黄金会员价等）');
            $table->decimal('market_price', 12, 2)->nullable()->comment('市场价');
            $table->decimal('cost_price', 12, 2)->nullable()->comment('成本价');

            // ========== 价格规则 ==========
            $table->json('quantity_tiers')->nullable()->comment('阶梯价格');
            $table->integer('priority')->default(0)->comment('优先级');

            $table->operator();
            $table->softDeletes();


            // 索引
            $table->index(['product_id', 'market', 'store', 'user_level'], 'idx_variant_price_dimensions');
            $table->index(['product_id', 'variant_id'], 'idx_variant_price_product_variant');

            $table->comment('商品变体-多维度价格表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_prices');
    }
};

