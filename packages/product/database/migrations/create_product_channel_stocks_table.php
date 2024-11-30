<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix','jasmine_') .'product_channel_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('channel');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->bigInteger('total_stock')->default(0)->comment('渠道总库存');
            $table->bigInteger('stock')->default(0)->comment('渠道可用库存');
            $table->bigInteger('lock_stock')->default(0)->comment('渠道锁定库存');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->comment('渠道库存');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix','jasmine_') .'product_channel_stocks');
    }
};
