<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->string('coupon_no')->unique()->comment('券码');
            $table->userMorphs('user', '用户', false);
            // 所有者信息


            $table->enum('status', UserCouponStatusEnum::values())
                  ->default(UserCouponStatusEnum::AVAILABLE)
                  ->comment(UserCouponStatusEnum::comments('状态'));
            $table->timestamp('issue_time')->useCurrent()->comment('发放时间');
            $table->timestamp('validity_start_time')->comment('有效期开始时间');
            $table->timestamp('validity_end_time')->comment('有效期结束时间');
            $table->timestamp('used_time')->nullable()->comment('使用时间');


            $table->string('order_no',64)->nullable()->comment('订单号');
            $table->string('order_product_no',64)->nullable()->comment('订单商品项');

            // TODO 领域信息预留
            $table->operator();

            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index(['user_id', 'status'], 'idx_user_status');
            $table->index('validity_end_time');
            $table->index('used_time');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('user_coupons');
    }
}; 