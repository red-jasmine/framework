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
        Schema::create('coupon_issue_stats', function (Blueprint $table) {
            // 所有者信息
            $table->string('owner_type', 50)->comment('所有者类型');
            $table->string('owner_id', 50)->comment('所有者ID');
            $table->unsignedBigInteger('coupon_id')->primary()->comment('优惠券ID');
            



            $table->integer('total_issued')->default(0)->comment('总发放数量');
            $table->integer('total_used')->default(0)->comment('总使用数量');
            $table->integer('total_expired')->default(0)->comment('总过期数量');
            $table->decimal('total_cost', 12, 2)->default(0.00)->comment('总成本');
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate()->comment('最后更新时间');


            // 操作者信息
            $table->operator();
            $table->index(['owner_type', 'owner_id'], 'idx_owner');

            $table->index('last_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_issue_stats');
    }
}; 