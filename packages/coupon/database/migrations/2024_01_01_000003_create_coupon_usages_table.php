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
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id()->comment('使用记录ID');
            
            // 所有者信息
            $table->string('owner_type', 50)->comment('所有者类型');
            $table->string('owner_id', 50)->comment('所有者ID');

            
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->unsignedBigInteger('user_coupon_id')->comment('用户优惠券ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->decimal('threshold_amount', 10, 2)->comment('门槛金额');
            $table->decimal('original_amount', 10, 2)->comment('原始金额');
            $table->decimal('discount_amount', 10, 2)->comment('优惠金额');
            $table->decimal('final_amount', 10, 2)->comment('最终金额');
            $table->timestamp('used_at')->useCurrent()->comment('使用时间');
            
            // 成本承担方信息
            $table->enum('cost_bearer_type', ['platform', 'merchant', 'broadcaster'])
                ->comment('成本承担方类型');
            $table->string('cost_bearer_id', 50)->comment('成本承担方ID');
            $table->string('cost_bearer_name', 100)->comment('成本承担方名称');

            $table->operator();
            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');

            $table->index('coupon_id');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('used_at');
            $table->index(['cost_bearer_type', 'cost_bearer_id'], 'idx_cost_bearer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
}; 