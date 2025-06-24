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
        Schema::create('invitation_destinations', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->unsignedBigInteger('invitation_code_id')->comment('邀请码ID');
            $table->enum('destination_type', ['register', 'product', 'activity', 'home', 'custom'])->comment('去向类型');
            $table->string('destination_id', 100)->nullable()->comment('目标ID');
            $table->string('destination_url', 1000)->nullable()->comment('目标URL');
            $table->enum('platform_type', ['web', 'h5', 'miniprogram', 'app'])->comment('平台类型');
            $table->json('platform_config')->nullable()->comment('平台配置');
            $table->boolean('is_default')->default(false)->comment('是否默认去向');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序');
            $table->timestamps();

            // 索引
            $table->index('invitation_code_id', 'idx_invitation_code_id');
            $table->index('platform_type', 'idx_platform_type');
            
            // 外键约束
            $table->foreign('invitation_code_id', 'fk_destinations_code_id')
                  ->references('id')
                  ->on('invitation_codes')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_destinations');
    }
}; 