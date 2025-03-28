<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_channel_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('channel');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->bigInteger('total_stock')->default(0)->comment('渠道总库存');
            $table->bigInteger('stock')->default(0)->comment('渠道可用库存');
            $table->bigInteger('lock_stock')->default(0)->comment('渠道锁定库存');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->comment('渠道库存');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_channel_stocks');
    }
};
