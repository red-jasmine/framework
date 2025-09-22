<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('member_type'); // 多态关联：成员类型
            $table->unsignedBigInteger('member_id'); // 多态关联：成员ID
            $table->enum('status', ProjectMemberStatus::values())->default(ProjectMemberStatus::PENDING->value);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->string('invited_by_type')->nullable(); // 邀请人类型
            $table->unsignedBigInteger('invited_by_id')->nullable(); // 邀请人ID
            $table->json('permissions')->nullable(); // 个人权限覆盖
            $table->timestamps();

            // 索引
            $table->unique(['project_id', 'member_type', 'member_id', 'left_at'], 'uk_project_member_active');
            $table->index(['member_type', 'member_id', 'project_id'], 'idx_member_project');
            $table->index(['project_id', 'status'], 'idx_project_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
