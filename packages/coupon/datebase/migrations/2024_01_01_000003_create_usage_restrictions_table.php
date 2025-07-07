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
        Schema::create('usage_restrictions', function (Blueprint $table) {
            $table->id()->comment('限制ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->enum('user_restriction', ['ALL', 'NEW_USER', 'MEMBER', 'SPECIFIC_USER'])->default('ALL')->comment('用户限制');
            $table->enum('product_restriction', ['ALL', 'SPECIFIC_PRODUCT', 'SPECIFIC_CATEGORY', 'EXCLUDE_PRODUCT', 'EXCLUDE_CATEGORY'])->default('ALL')->comment('商品限制');
            $table->enum('overlay_rule', ['ALLOW', 'DISALLOW', 'LIMIT'])->default('DISALLOW')->comment('叠加规则');
            $table->integer('user_level_min')->nullable()->comment('用户等级最小值');
            $table->integer('user_level_max')->nullable()->comment('用户等级最大值');
            $table->integer('register_days_min')->nullable()->comment('注册天数最小值');
            $table->integer('register_days_max')->nullable()->comment('注册天数最大值');
            $table->decimal('consumption_amount_min', 10, 2)->nullable()->comment('消费金额最小值');
            $table->decimal('consumption_amount_max', 10, 2)->nullable()->comment('消费金额最大值');
            $table->json('specific_user_ids')->nullable()->comment('指定用户ID列表');
            $table->json('specific_product_ids')->nullable()->comment('指定商品ID列表');
            $table->json('specific_category_ids')->nullable()->comment('指定类目ID列表');
            $table->json('exclude_product_ids')->nullable()->comment('排除商品ID列表');
            $table->json('exclude_category_ids')->nullable()->comment('排除类目ID列表');
            $table->timestamps();

            // 索引
            $table->unique('coupon_id', 'uk_coupon_id');
            $table->index('user_restriction', 'idx_user_restriction');
            $table->index('product_restriction', 'idx_product_restriction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_restrictions');
    }
}; 