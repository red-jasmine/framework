<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('promotion_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->enum('type', ActivityTypeEnum::values())->comment(ActivityTypeEnum::comments('活动类型'));

            $table->string('title')->comment('活动标题');
            $table->text('description')->nullable()->comment('活动描述');

            $table->userMorphs('owner', '所属者', false, false);

            // 活动时间
            $table->datetime('sign_up_start_time')->nullable()->comment('报名开始时间');
            $table->datetime('sign_up_end_time')->nullable()->comment('报名结束时间');
            $table->datetime('start_time')->comment('活动开始时间');
            $table->datetime('end_time')->comment('活动结束时间');

            // 活动要求
            $table->json('activity_rules')->nullable()->comment('活动规则');
            $table->json('product_rules')->nullable()->comment('商品规则');
            $table->json('shop_rules')->nullable()->comment('店铺规则');
            $table->json('user_rules')->nullable()->comment('用户规则');

            // 状态管理
            $table->string('status', ActivityStatusEnum::values())->default(ActivityStatusEnum::DRAFT)->comment(ActivityStatusEnum::comments('状态'));
            $table->boolean('is_show')->default(true)->comment('是否展示');

            // 数据统计
            $table->unsignedBigInteger('total_products')->default(0)->comment('参与商品总数');
            $table->unsignedBigInteger('total_users')->default(0)->comment('总参与用户数');
            $table->unsignedBigInteger('total_orders')->default(0)->comment('总订单数');
            $table->unsignedBigInteger('total_sales')->default(0)->comment('总销量');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('总销售金额');


            $table->decimal('total_amount', 12, 2)->default(0)->comment('销售金额');

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
    public function down() : void
    {
        Schema::dropIfExists('promotion_activities');
    }
};
