<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') . 'product_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('标签');
            $table->timestamps();
            $table->comment('商品标签');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') . 'product_tags');
    }
};
