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
        Schema::create('invitation_usage_logs', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->unsignedBigInteger('invitation_code_id')->comment('邀请码ID');
            $table->string('invitation_code', 50)->comment('邀请码');
            $table->string('user_type', 50)->nullable()->comment('用户类型');
            $table->string('user_id', 100)->nullable()->comment('用户ID');
            $table->string('user_name', 100)->nullable()->comment('用户名称');
            $table->string('visitor_id', 100)->nullable()->comment('访客ID');
            $table->string('session_id', 100)->nullable()->comment('会话ID');
            $table->enum('action_type', ['visit', 'register', 'order', 'share'])->comment('操作类型');
            $table->enum('platform_type', ['web', 'h5', 'miniprogram', 'app'])->comment('平台类型');
            $table->string('ip_address', 45)->nullable()->comment('IP地址');
            $table->text('user_agent')->nullable()->comment('用户代理');
            $table->string('referer', 1000)->nullable()->comment('来源页面');
            $table->json('extra_data')->nullable()->comment('额外数据');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');

            // 索引
            $table->index('invitation_code_id', 'idx_invitation_code_id');
            $table->index('invitation_code', 'idx_invitation_code');
            $table->index(['user_type', 'user_id'], 'idx_user');
            $table->index('visitor_id', 'idx_visitor_id');
            $table->index('action_type', 'idx_action_type');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_usage_logs');
    }
}; 