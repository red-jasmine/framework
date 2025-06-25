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
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('code', 32)->unique()->comment('邀请码');
            $table->string('code_type', 20)->default('system')->comment('邀请码类型');
            $table->string('status', 20)->default('active')->comment('状态');
            
            // 邀请人信息
            $table->string('owner_type', 100)->comment('邀请人类型');
            $table->unsignedBigInteger('owner_id')->comment('邀请人ID');
            $table->string('owner_nickname', 100)->nullable()->comment('邀请人昵称');
            $table->string('owner_avatar', 500)->nullable()->comment('邀请人头像');
            
            // 使用控制
            $table->unsignedInteger('max_usage')->default(0)->comment('最大使用次数');
            $table->unsignedInteger('used_count')->default(0)->comment('已使用次数');
            $table->timestamp('expired_at')->nullable()->comment('过期时间');
            
            // 扩展信息
            $table->json('extra')->nullable()->comment('扩展数据');
            $table->string('description', 500)->nullable()->comment('描述');
            
            // 操作人信息

            $table->operator();
            


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