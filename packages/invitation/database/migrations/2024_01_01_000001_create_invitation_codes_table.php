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
        Schema::create('invitation_codes', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->string('code', 50)->unique()->comment('邀请码');
            $table->string('inviter_type', 50)->comment('邀请人类型');
            $table->string('inviter_id', 100)->comment('邀请人ID');
            $table->string('inviter_name', 100)->comment('邀请人名称');
            $table->string('title', 200)->comment('邀请标题');
            $table->text('description')->nullable()->comment('邀请描述');
            $table->string('slogan', 500)->nullable()->comment('广告语');
            $table->enum('generate_type', ['custom', 'system'])->default('system')->comment('生成类型');
            $table->unsignedInteger('max_usage')->default(0)->comment('最大使用次数，0表示无限制');
            $table->unsignedInteger('used_count')->default(0)->comment('已使用次数');
            $table->timestamp('expires_at')->nullable()->comment('过期时间');
            $table->enum('status', ['active', 'disabled', 'expired'])->default('active')->comment('状态');
            $table->json('tags')->nullable()->comment('标签信息');
            $table->json('extra_data')->nullable()->comment('扩展数据');
            $table->timestamps();

            // 索引
            $table->index(['inviter_type', 'inviter_id'], 'idx_inviter');
            $table->index(['status', 'expires_at'], 'idx_status_expires');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_codes');
    }
}; 