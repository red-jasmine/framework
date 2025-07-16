<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Coupon\Domain\Models\Enums\CouponGetTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\CouponTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('coupon_user_coupons', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->enum('coupon_type', CouponTypeEnum::values())->comment(CouponTypeEnum::comments('类型'));
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->enum('discount_level', DiscountLevelEnum::values())->comment(DiscountLevelEnum::comments('优惠目标类型'));

            $table->userMorphs('user', '用户', false);
            $table->enum('coupon_get_type', CouponGetTypeEnum::values())
                  ->comment(CouponGetTypeEnum::comments('获得方式'));
            $table->string('coupon_no')->unique()->comment('券码');
            // 所有者信息

            $table->enum('status', UserCouponStatusEnum::values())
                  ->default(UserCouponStatusEnum::AVAILABLE)
                  ->comment(UserCouponStatusEnum::comments('状态'));
            $table->timestamp('issue_time')->useCurrent()->comment('发放时间');
            $table->timestamp('validity_start_time')->comment('有效期开始时间');
            $table->timestamp('validity_end_time')->comment('有效期结束时间');
            $table->timestamp('used_time')->nullable()->comment('使用时间');
            // TODO 领域信息预留
            $table->operator();

            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index(['user_id', 'status'], 'idx_user_status');
            $table->index('validity_end_time');
            $table->index('used_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('coupon_user_coupons');
    }
}; 