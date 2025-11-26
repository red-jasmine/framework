<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->string('locale', 10)->comment('语言代码：zh-CN, en-US, de-DE, ja-JP');

            // ========== 基础内容（来自 products 表）==========
            $table->string('title', 255)->comment('商品标题');
            $table->string('slogan', 255)->nullable()->comment('广告语/副标题');

            // ========== 详情内容（来自 products_extension 表）==========
            $table->longText('description')->nullable()->comment('富文本详情（HTML格式，详细描述）');

            // ========== SEO 相关（来自 products_extension 表）==========
            $table->string('meta_title', 255)->nullable()->comment('SEO标题');
            $table->string('meta_keywords', 255)->nullable()->comment('SEO关键词');
            $table->text('meta_description')->nullable()->comment('SEO描述');

            // ========== 翻译状态 ==========
            $table->string('translation_status', 32)->default('pending')->comment(TranslationStatusEnum::comments('翻译状态'));
            $table->timestamp('translated_at')->nullable()->comment('翻译完成时间');
            $table->timestamp('reviewed_at')->nullable()->comment('审核完成时间');

            $table->operator();
            $table->softDeletes();

            // 索引
            // 注意：由于使用软删除，唯一索引需要排除已删除的记录
            // 实际唯一性约束通过应用层保证，这里只创建普通索引
            $table->index(['product_id', 'locale'], 'idx_product_locale');
            $table->index('locale', 'idx_locale');
            $table->index('translation_status', 'idx_translation_status');
            $table->index('product_id', 'idx_product');
            $table->fullText(['title', 'description'], 'idx_search');

            $table->comment('商品-翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};

