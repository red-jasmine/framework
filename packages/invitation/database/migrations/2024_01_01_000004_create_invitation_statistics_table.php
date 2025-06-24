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
        Schema::create('invitation_statistics', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->unsignedBigInteger('invitation_code_id')->comment('邀请码ID');
            $table->date('stat_date')->comment('统计日期');
            $table->unsignedInteger('visit_count')->default(0)->comment('访问次数');
            $table->unsignedInteger('unique_visitor_count')->default(0)->comment('独立访客数');
            $table->unsignedInteger('register_count')->default(0)->comment('注册数');
            $table->unsignedInteger('order_count')->default(0)->comment('下单数');
            $table->decimal('order_amount', 10, 2)->default(0.00)->comment('订单金额');
            $table->unsignedInteger('share_count')->default(0)->comment('分享次数');
            $table->decimal('conversion_rate', 5, 4)->default(0.0000)->comment('转化率');
            $table->timestamps();

            // 索引
            $table->unique(['invitation_code_id', 'stat_date'], 'uk_code_date');
            $table->index('stat_date', 'idx_stat_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_statistics');
    }
}; 