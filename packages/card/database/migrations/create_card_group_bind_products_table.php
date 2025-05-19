<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('card_group_bind_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->unsignedBigInteger('group_id')->comment('卡密分组ID');
            $table->string('product_type', 64);
            $table->string('product_id', 64);
            $table->string('sku_id',64)->default(0)->comment('SKU-ID');

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->comment('卡密分组绑定商品');
            $table->unique(['owner_id', 'owner_type', 'product_type', 'product_id', 'sku_id'], 'uk_owner_product_sku');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('card_group_bind_products');
    }
};
