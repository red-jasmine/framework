<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Project\Domain\Models\Enums\ProjectStatus;
use RedJasmine\Project\Domain\Models\Enums\ProjectType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type'); // 多态关联：组织类型
            $table->unsignedBigInteger('owner_id'); // 多态关联：组织ID
            $table->unsignedBigInteger('parent_id')->nullable(); // 支持项目分组
            $table->string('name');
            $table->string('short_name', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('code', 100); // 所有者内唯一
            $table->enum('project_type', ProjectType::values())->default(ProjectType::STANDARD->value);
            $table->enum('status', ProjectStatus::values())->default(ProjectStatus::DRAFT->value);
            $table->integer('sort')->default(0);
            $table->json('config')->nullable(); // 项目配置
            $table->timestamps();
            $table->softDeletes();

            // 索引
            $table->unique(['owner_type', 'owner_id', 'code'], 'uk_owner_code');
            $table->index(['owner_type', 'owner_id', 'status'], 'idx_owner_status');
            $table->index('parent_id', 'idx_parent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
