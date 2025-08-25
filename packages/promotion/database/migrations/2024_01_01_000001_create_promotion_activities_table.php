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
        Schema::create('promotion_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('title')->comment('活动标题');
            $table->text('description')->nullable()->comment('活动描述');
            $table->string('type', 50)->comment('活动类型 (flash_sale, group_buying, bargain, discount, full_reduction, bundle)');
            $table->string('owner_type', 64)->comment('发起方类型 (platform, merchant)');
            $table->string('owner_id', 64)->comment('发起方ID');
            
            // 客户端信息
            $table->string('client_type', 64)->nullable()->comment('客户端类型');
            $table->string('client_id', 64)->nullable()->comment('客户端ID');
            
            // 活动时间
            $table->datetime('sign_up_start_time')->nullable()->comment('报名开始时间');
            $table->datetime('sign_up_end_time')->nullable()->comment('报名结束时间');
            $table->datetime('start_time')->comment('活动开始时间');
            $table->datetime('end_time')->comment('活动结束时间');
            
            // 活动要求
            $table->json('product_requirements')->nullable()->comment('商品报名要求');
            $table->json('shop_requirements')->nullable()->comment('店铺报名要求');
            $table->json('user_requirements')->nullable()->comment('用户参与要求');
            
            // 活动规则
            $table->json('rules')->nullable()->comment('活动规则');
            $table->json('overlay_rules')->nullable()->comment('优惠叠加规则');
            
            // 状态管理
            $table->string('status', 32)->default('draft')->comment('活动状态 (draft, pending, published, warming, running, paused, ended, cancelled)');
            $table->boolean('is_show')->default(true)->comment('是否展示');
            
            // 数据统计
            $table->integer('total_products')->default(0)->comment('参与商品总数');
            $table->integer('total_orders')->default(0)->comment('总订单数');
            $table->decimal('total_sales', 12, 2)->default(0.00)->comment('总销售额');
            $table->integer('total_participants')->default(0)->comment('总参与人数');
            
            // 操作信息
            $table->operator();
            $table->softDeletes();
            
            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index(['creator_type', 'creator_id'], 'idx_creator');
            $table->index(['start_time', 'end_time'], 'idx_time');
            $table->index(['status'], 'idx_status');
            $table->index(['type'], 'idx_type');
            $table->index(['status', 'start_time', 'end_time'], 'idx_activities_status_time');
            $table->comment('促销活动表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_activities');
    }
};
