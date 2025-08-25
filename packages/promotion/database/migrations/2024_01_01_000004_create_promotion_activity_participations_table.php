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
        Schema::create('promotion_activity_participations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('activity_id')->comment('活动ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->nullable()->comment('SKU ID');
            $table->userMorphs('user', '用户', false);
            
            // 参与信息
            $table->string('order_no', 64)->nullable()->comment('订单号');
            $table->integer('quantity')->default(1)->comment('参与数量');
            $table->decimal('amount', 10, 2)->comment('参与金额');
            $table->timestamp('participated_at')->nullable()->comment('参与时间');
            
            // 活动信息快照
            $table->decimal('activity_price', 10, 2)->nullable()->comment('活动价格快照');
            $table->decimal('discount_rate', 5, 2)->nullable()->comment('折扣率快照');
            
            // 状态
            $table->string('status', 32)->default('participated')->comment('参与状态 (participated, ordered, completed, cancelled)');
            
            // 操作信息
            $table->operator();
            $table->softDeletes();
            
            // 索引
            $table->index(['activity_id'], 'idx_activity');
            $table->index(['user_type', 'user_id'], 'idx_user');
            $table->index(['product_id'], 'idx_product');
            $table->index(['sku_id'], 'idx_sku');
            $table->index(['order_no'], 'idx_order');
            $table->index(['participated_at'], 'idx_participated_at');
            $table->index(['creator_type', 'creator_id'], 'idx_creator');
            $table->index(['user_id', 'activity_id'], 'idx_participations_user_activity');
            $table->comment('促销活动参与记录表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_activity_participations');
    }
};
