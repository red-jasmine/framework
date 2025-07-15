<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;

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
            $table->string('description')->nullable()->comment('描述');
            $table->string('image')->nullable()->comment('优惠券图片');
            $table->boolean('is_show')->default(true)->comment('是否显示');
            $table->enum('status',
                CouponStatusEnum::values())->default(CouponStatusEnum::DRAFT)->comment(CouponStatusEnum::comments('状态'));


            $table->enum('discount_level', DiscountLevelEnum::values())->comment(DiscountLevelEnum::comments('优惠目标类型'));

            $table->enum('discount_amount_type', DiscountAmountTypeEnum::values())
                  ->comment(DiscountAmountTypeEnum::comments('优惠金额类型'));
            $table->enum('threshold_type', ThresholdTypeEnum::values())->comment(ThresholdTypeEnum::comments('门槛类型'));
            $table->decimal('threshold_value', 10)->comment('门槛值');
            $table->decimal('discount_amount_value', 10)->comment('优惠金额值');
            $table->decimal('max_discount_amount', 10)->nullable()->comment('最大优惠金额');

            // 使用时间限制
            $table->enum('validity_type', ValidityTypeEnum::values())->comment(ValidityTypeEnum::comments('有效期类型'));
            $table->timestamp('validity_start_time')->nullable()->comment('绝对有效期开始时间');
            $table->timestamp('validity_end_time')->nullable()->comment('绝对有效期结束时间');

            $table->enum('delayed_effective_time_unit',
                TimeUnitEnum::values())->nullable()->comment(TimeUnitEnum::comments('延迟生效时间类型'));
            $table->unsignedBigInteger('delayed_effective_time_value')->nullable()->comment('延迟生效时间值');

            $table->enum('validity_time_unit', TimeUnitEnum::values())->nullable()->comment(TimeUnitEnum::comments('相对有效期时间类型'));
            $table->unsignedBigInteger('validity_time_value')->nullable()->comment('相对有效期时间值');


            // 使用规则 如: 正对于 部分商品、部分分类、部分用户、部分渠道等。。。
            $table->json('usage_rules')->nullable()->comment('使用规则配置');

            // 领取规则 如：限制用户领域
            $table->json('receive_rules')->nullable()->comment('领取规则配置');

            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('remarks')->nullable()->comment('备注');
            // 成本承担方
            $table->userMorphs('cost_bearer', '成本承担方');
            // 发放控制
            $table->unsignedBigInteger('total_quantity')->comment('总数量');
            $table->unsignedBigInteger('total_issued')->default(0)->comment('总发放数量');
            $table->unsignedBigInteger('total_used')->default(0)->comment('总使用数量');


            // 优惠券
            $table->timestamp('start_time')->comment('开始时间');
            $table->timestamp('end_time')->comment('结束时间');
            $table->timestamp('published_time')->nullable()->comment('发布时间');

            $table->operator();


            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
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