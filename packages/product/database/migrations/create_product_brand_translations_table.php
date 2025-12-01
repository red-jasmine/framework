<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_brand_translations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('product_brand_id')->comment('品牌ID');
            $table->string('locale', 10)->comment('语言代码');

            // ========== 可翻译字段 ==========
            $table->string('name', 255)->comment('品牌名称');
            $table->string('slogan', 255)->nullable()->comment('品牌口号');
            $table->text('description')->nullable()->comment('品牌描述');


            // ========== 翻译状态 ==========
            $table->string('translation_status', 32)->default(TranslationStatusEnum::PENDING->value)->comment(TranslationStatusEnum::comments('翻译状态'));
            $table->timestamp('translated_at')->nullable()->comment('翻译完成时间');
            $table->timestamp('reviewed_at')->nullable()->comment('审核完成时间');

            $table->operator();
            $table->softDeletes();

            // 索引
            // 注意：由于使用软删除，唯一索引需要排除已删除的记录
            // 实际唯一性约束通过应用层保证，这里只创建普通索引
            $table->index(['product_brand_id', 'locale'], 'idx_brand_locale');
            $table->index('locale', 'idx_locale');
            $table->index('translation_status', 'idx_translation_status');
            $table->index('product_brand_id', 'idx_product_brand');
            $table->fullText(['name', 'description'], 'idx_search');

            $table->comment('商品品牌-翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_brand_translations');
    }
};

