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
        Schema::create('coupon_statistics', function (Blueprint $table) {
            $table->id()->comment('统计ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->integer('issued_quantity')->default(0)->comment('发放数量');
            $table->integer('used_quantity')->default(0)->comment('使用数量');
            $table->decimal('usage_rate', 5, 4)->default(0.0000)->comment('使用率');
            $table->decimal('conversion_rate', 5, 4)->default(0.0000)->comment('转化率');
            $table->decimal('total_discount_amount', 12, 2)->default(0.00)->comment('总优惠金额');
            $table->decimal('total_cost_amount', 12, 2)->default(0.00)->comment('总成本金额');
            $table->decimal('roi', 8, 4)->default(0.0000)->comment('投资回报率');
            $table->json('daily_stats')->nullable()->comment('每日统计数据');
            $table->timestamps();

            // 索引
            $table->unique('coupon_id', 'uk_coupon_id');
            $table->index('usage_rate', 'idx_usage_rate');
            $table->index('conversion_rate', 'idx_conversion_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_statistics');
    }
}; 