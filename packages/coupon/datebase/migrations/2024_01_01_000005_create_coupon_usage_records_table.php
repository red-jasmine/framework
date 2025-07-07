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
        Schema::create('coupon_usage_records', function (Blueprint $table) {
            $table->id()->comment('记录ID');
            $table->unsignedBigInteger('user_coupon_id')->comment('用户优惠券ID');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->decimal('use_amount', 10, 2)->comment('使用金额');
            $table->decimal('discount_amount', 10, 2)->comment('优惠金额');
            $table->timestamp('use_time')->useCurrent()->comment('使用时间');
            $table->timestamp('verify_time')->nullable()->comment('核销时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->decimal('refund_amount', 10, 2)->nullable()->comment('退款金额');
            $table->timestamps();

            // 索引
            $table->index('user_coupon_id', 'idx_user_coupon_id');
            $table->index('order_id', 'idx_order_id');
            $table->index('use_time', 'idx_use_time');
            $table->index('verify_time', 'idx_verify_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usage_records');
    }
}; 