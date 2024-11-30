<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix','jasmine_') .'product_series', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->morphs('owner');
            $table->string('name')->comment('系列名称');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->comment('商品-系列表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix','jasmine_') .'product_series');
    }
};
