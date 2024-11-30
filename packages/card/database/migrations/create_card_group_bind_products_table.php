<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-card.tables.prefix','jasmine_').'card_group_bind_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->morphs('owner');
            $table->morphs('product');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('SKU-ID');
            $table->unsignedBigInteger('group_id')->comment('卡密分组ID');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->comment('卡密分组绑定商品');
            $table->unique([ 'owner_id', 'owner_type', 'product_type', 'product_id', 'sku_id' ], 'uk_owner_product_sku');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-card.tables.prefix','jasmine_').'card_group_bind_products');
    }
};
