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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id()->comment('优惠券ID');

            // 所有者信息
            $table->string('owner_type', 64)->comment('所有者类型');
            $table->string('owner_id', 32)->comment('所有者ID');
            $table->string('name', 100)->comment('优惠券名称');
            $table->text('description')->nullable()->comment('优惠券描述');
            $table->string('image')->nullable()->comment('优惠券图片');
            $table->enum('status', ['draft', 'published', 'paused', 'expired'])
                  ->default('draft')
                  ->comment('状态');

            // 优惠规则
            $table->enum('discount_target', ['order_amount', 'product_amount', 'shipping_amount', 'cross_store_amount'])
                  ->comment('优惠目标');
            $table->enum('discount_type', ['fixed_amount', 'percentage'])->comment('优惠类型 折扣、满减');

            $table->decimal('threshold_amount', 10)->default(0)->comment('门槛金额');
            $table->decimal('discount_value', 10, 2)->comment('优惠值');
            $table->decimal('max_discount_amount', 10, 2)->nullable()->comment('最大优惠金额');
            $table->boolean('is_ladder')->default(false)->comment('是否阶梯优惠');
            $table->json('ladder_rules')->nullable()->comment('更多阶梯规则配置');


            // 有效期规则
            $table->enum('validity_type', ['absolute', 'relative'])->comment('有效期类型');
            $table->dateTime('start_time')->nullable()->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->string('relative_time_type',32)->nullable()->comment('相对时间类型');
            $table->integer('relative_time_value')->nullable()->comment('相对时间值');



            // 使用限制
            $table->integer('max_usage_per_user')->default(1)->comment('每用户最大使用次数');
            $table->integer('max_usage_total')->nullable()->comment('总使用次数限制');

            // 使用规则
            $table->json('usage_rules')->nullable()->comment('使用规则配置');

            // 领取规则
            $table->json('collect_rules')->nullable()->comment('领取规则配置');

            // 成本承担方
            $table->enum('cost_bearer_type', ['platform', 'merchant', 'broadcaster'])
                  ->comment('成本承担方类型');
            $table->string('cost_bearer_id', 50)->comment('成本承担方ID');
            $table->string('cost_bearer_name', 100)->comment('成本承担方名称');

            // 发放控制
            $table->enum('issue_strategy', ['auto', 'manual', 'code'])
                  ->default('manual')
                  ->comment('发放策略');
            $table->integer('total_issue_limit')->nullable()->comment('总发放限制');
            $table->integer('current_issue_count')->default(0)->comment('当前发放数量');


            $table->operator();
            $table->timestamps();

            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index(['status', 'start_time', 'end_time'], 'idx_status_time');
            $table->index(['cost_bearer_type', 'cost_bearer_id'], 'idx_cost_bearer');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('coupons');
    }
}; 