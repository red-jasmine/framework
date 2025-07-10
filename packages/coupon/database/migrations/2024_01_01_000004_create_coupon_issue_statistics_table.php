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
        Schema::create('coupon_issue_statistics', function (Blueprint $table) {
            // 所有者信息
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->datetime('date')->comment('统计日期');
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->unsignedBigInteger('total_issued')->default(0)->comment('总发放数量');
            $table->unsignedBigInteger('total_expired')->default(0)->comment('总过期数量');
            $table->unsignedBigInteger('total_used')->default(0)->comment('总使用数量');
            $table->decimal('total_cost', 12)->default(0.00)->comment('总成本');
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate()->comment('最后更新时间');

            // 操作者信息
            $table->operator();
            $table->unique(['coupon_id', 'date'], 'uk_coupon_date');
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index('last_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('coupon_issue_statistics');
    }
}; 