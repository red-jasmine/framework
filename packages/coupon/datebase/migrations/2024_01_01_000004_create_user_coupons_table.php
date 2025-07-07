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
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->id()->comment('用户优惠券ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->string('coupon_code', 50)->comment('优惠券码');
            $table->enum('status', ['UNUSED', 'USED', 'EXPIRED', 'REFUNDED'])->default('UNUSED')->comment('状态');
            $table->timestamp('issue_time')->useCurrent()->comment('发放时间');
            $table->timestamp('use_time')->nullable()->comment('使用时间');
            $table->timestamp('expire_time')->comment('过期时间');
            $table->unsignedBigInteger('order_id')->nullable()->comment('使用订单ID');
            $table->decimal('use_amount', 10, 2)->nullable()->comment('使用金额');
            $table->decimal('discount_amount', 10, 2)->nullable()->comment('优惠金额');
            $table->timestamps();

            $table->comment('用户优惠券表');

            // 索引
            $table->unique('coupon_code', 'uk_coupon_code');
            $table->index('user_id', 'idx_user_id');
            $table->index('coupon_id', 'idx_coupon_id');
            $table->index('status', 'idx_status');
            $table->index('expire_time', 'idx_expire_time');
            $table->index('issue_time', 'idx_issue_time');
            $table->index('order_id', 'idx_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
    }
}; 