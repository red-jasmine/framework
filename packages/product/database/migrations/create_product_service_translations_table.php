<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_service_translations', function (Blueprint $table) {
            $table->categoryTranslations('商品服务翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_service_translations');
    }
};

