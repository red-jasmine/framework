<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_attribute_value_translations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('value_id')->comment('属性值ID');
            $table->string('locale', 10)->comment('语言代码');

            // 翻译字段
            $table->string('name', 64)->comment('名称');
            $table->string('description')->nullable()->comment('描述');

            // ========== 翻译状态 ==========
            $table->string('translation_status', 32)->default(TranslationStatusEnum::REVIEWED->value)->comment(TranslationStatusEnum::comments('翻译状态'));
            $table->timestamp('translated_at')->nullable()->comment('翻译完成时间');
            $table->timestamp('reviewed_at')->nullable()->comment('审核完成时间');

            $table->operator();
            $table->softDeletes();

            // 索引
            $table->index(['value_id', 'locale'], 'idx_value_locale');
            $table->index('locale', 'idx_locale');
            $table->index('translation_status', 'idx_translation_status');
            $table->index('value_id', 'idx_value');
            $table->fullText(['name', 'description'], 'idx_search');

            $table->comment('商品属性值翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_value_translations');
    }
};

