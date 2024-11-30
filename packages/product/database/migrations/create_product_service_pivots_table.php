<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix','jasmine_') . 'product_service_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_service_id');
            $table->timestamps();
            $table->index('product_id', 'idx_product');
            $table->index('product_service_id', 'idx_product_service');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix','jasmine_') . 'product_service_pivots');
    }
};
