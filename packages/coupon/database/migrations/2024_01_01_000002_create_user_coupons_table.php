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
            
            // 所有者信息
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            

            
            $table->string('user_type', 32)->comment('用户类型');
            $table->string('user_id', 64)->comment('用户ID');
        
            $table->enum('status', ['available', 'used', 'expired'])
                ->default('available')
                ->comment('状态');
            $table->timestamp('issue_time')->useCurrent()->comment('发放时间');
            $table->timestamp('expire_time')->comment('过期时间');
            $table->timestamp('used_time')->nullable()->comment('使用时间');
            $table->unsignedBigInteger('order_id')->nullable()->comment('使用订单ID');

            $table->operator();

            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index(['user_id', 'status'], 'idx_user_status');
            $table->index('expire_time');
            $table->index('used_time');
            $table->index('order_id');
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