<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_group_translations', function (Blueprint $table) {
            $table->categoryTranslations('商品分组翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_group_translations');
    }
};

