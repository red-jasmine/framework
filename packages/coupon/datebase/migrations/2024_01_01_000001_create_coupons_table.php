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
        Schema::create('coupons', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('优惠券ID');
            $table->string('name', 100)->comment('优惠券名称');
            $table->text('description')->nullable()->comment('优惠券描述');
            $table->enum('coupon_type', ['DISCOUNT', 'FULL_REDUCTION', 'FREE_SHIPPING'])->comment('优惠券类型：折扣券/满减券/包邮券');
            $table->enum('cost_bearer', ['PLATFORM', 'MERCHANT', 'ANCHOR'])->comment('成本承担方');
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'DISABLED', 'DELETED'])->default('DRAFT')->comment('状态');
            $table->string('owner_type', 50)->comment('所有者类型');
            $table->unsignedBigInteger('owner_id')->comment('所有者ID');
            $table->string('operator_type', 50)->nullable()->comment('操作者类型');
            $table->unsignedBigInteger('operator_id')->nullable()->comment('操作者ID');
            $table->decimal('threshold_amount', 10)->default(0.00)->comment('使用门槛金额');
            $table->decimal('discount_amount', 10)->default(0.00)->comment('优惠金额');
            $table->decimal('discount_rate', 6, 4)->nullable()->comment('折扣比例');
            $table->enum('validity_type', ['ABSOLUTE', 'RELATIVE'])->comment('有效期类型');
            $table->timestamp('start_time')->nullable()->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment('结束时间');
            $table->integer('relative_days')->nullable()->comment('相对天数');
            $table->unsignedBigInteger('quantity')->comment('数量');

            $table->unsignedBigInteger('Receive_quantity')->comment('领取数量');
            $table->integer('daily_limit')->nullable()->comment('每日限量');
            $table->integer('personal_limit')->default(1)->comment('个人限量');

            $table->timestamps();

            $table->operator();


            $table->softDeletes();

            // 索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index('status', 'idx_status');
            $table->index('coupon_type', 'idx_coupon_type');
            $table->index('cost_bearer', 'idx_cost_bearer');
            $table->index('created_at', 'idx_created_at');
            $table->index('start_time', 'idx_start_time');
            $table->index('end_time', 'idx_end_time');
            $table->index('validity_type', 'idx_validity_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
}; 