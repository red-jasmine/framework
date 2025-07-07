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
        Schema::create('coupon_issue_logs', function (Blueprint $table) {
            $table->id()->comment('日志ID');
            $table->unsignedBigInteger('coupon_id')->comment('优惠券ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->enum('issue_method', ['AUTO', 'MANUAL', 'ACTIVITY'])->comment('发放方式');
            $table->timestamp('issue_time')->useCurrent()->comment('发放时间');
            $table->string('operator_type', 50)->nullable()->comment('操作者类型');
            $table->unsignedBigInteger('operator_id')->nullable()->comment('操作者ID');
            $table->string('ip_address', 45)->nullable()->comment('IP地址');
            $table->text('user_agent')->nullable()->comment('用户代理');
            $table->timestamp('created_at')->nullable()->comment('创建时间');

            // 索引
            $table->index('coupon_id', 'idx_coupon_id');
            $table->index('user_id', 'idx_user_id');
            $table->index('issue_time', 'idx_issue_time');
            $table->index('issue_method', 'idx_issue_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_issue_logs');
    }
}; 