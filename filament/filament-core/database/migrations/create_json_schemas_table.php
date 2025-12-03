<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('json_schemas', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('name')->comment('名称');
            $table->string('slug')->nullable()->unique()->comment('标识');
            $table->string('title')->nullable()->comment('标题');
            $table->text('description')->nullable()->comment('描述');
            $table->json('schema')->comment('JSON Schema 结构');

            $table->json('extra')->nullable()->comment('扩展字段');

            $table->operator();
            $table->softDeletes();

            $table->index('slug', 'idx_slug');
            $table->comment('JSON Schema 表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('json_schemas');
    }
};

