<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Project\Domain\Models\Enums\ProjectRoleStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable(); // NULL表示全局角色
            $table->string('name');
            $table->string('code', 100);
            $table->string('description');
            $table->boolean('is_system')->default(false); // 是否为系统角色
            $table->json('permissions')->nullable(); // 角色权限
            $table->integer('sort')->default(0);
            $table->enum('status', ProjectRoleStatus::values())->default(ProjectRoleStatus::ACTIVE->value);
            $table->timestamps();

            // 索引
            $table->unique(['project_id', 'code'], 'uk_project_code');
            $table->index(['project_id', 'status'], 'idx_project_status');
            $table->index('is_system', 'idx_is_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_roles');
    }
};
