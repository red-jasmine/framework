<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->string('coupon_no')->unique()->comment('券码');
            $table->userMorphs('user', '用户', false);

            $table->string('order_no')->comment('订单号');
            $table->decimal('threshold_amount', 10)->comment('门槛金额');
            $table->decimal('discount_amount', 10)->comment('优惠金额');
            $table->decimal('final_discount_amount', 10)->comment('最终优惠金额');
            $table->timestamp('used_at')->useCurrent()->comment('使用时间');
            // 成本承担方信息
            $table->userMorphs('cost_bearer', '成本承担方');

            $table->operator();
            // 索引
            $table->index(['owner_id', 'owner_type'], 'idx_owner');
            $table->index(['user_id', 'user_type'], 'idx_user');
            $table->index('coupon_id');
            $table->index('order_no', 'idx_order_no');
            $table->index('used_at');
            $table->index(['cost_bearer_id', 'cost_bearer_type'], 'idx_cost_bearer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('coupon_usages');
    }
}; 