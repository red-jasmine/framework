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
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->userMorphs('owner','所属者',true,false);
            $table->string('name', 100)->comment('模板名称');
            $table->string('title')->comment('标题模板');
            $table->text('content_template')->comment('内容模板');
            $table->json('variables')->nullable()->comment('模板变量定义');
            $table->enum('status', ['enable', 'disable'])->default('enable')->comment('状态');
            $table->timestamps();
            $table->softDeletes();

            // 索引
            $table->unique('name', 'uk_name');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_templates');
    }
};
