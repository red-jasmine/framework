<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Coupon\Domain\Models\Enums\CouponGetTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\CouponTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->enum('coupon_type', CouponTypeEnum::values())->comment(CouponTypeEnum::comments('类型'));
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->userMorphs('user', '用户', false);
            $table->string('coupon_no')->comment('券码');
            // 成本承担方信息
            $table->userMorphs('cost_bearer', '成本承担方');

            $table->string('order_type', 64)->nullable()->comment('订单员类型');
            $table->string('order_no', 64)->nullable()->comment('订单号');
            $table->string('order_product_no', 64)->nullable()->comment('订单商品项');
            $table->string('discount_amount_currency', 3)->comment('优惠金额');
            $table->decimal('discount_amount_amount', 10)->comment('优惠金额');
            $table->timestamp('used_at')->useCurrent()->comment('使用时间');


            $table->operator();
            // 索引
            $table->index(['owner_id', 'owner_type'], 'idx_owner');
            $table->index(['user_id', 'user_type'], 'idx_user');
            $table->index('coupon_id', 'idx_coupon_id');
            $table->index('coupon_no', 'idx_coupon_no');
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