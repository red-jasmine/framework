<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->string('change_type', 32)->comment('更变类型');
            $table->string('change_detail')->nullable()->comment('变更明细');
            $table->bigInteger('stock')->comment('库存');
            $table->nullableMorphs('channel');
            $table->unsignedTinyInteger('is_lock')->default(0)->comment('是否操作锁定');

            $table->nullableMorphs('creator');
            $table->timestamps();
            $table->comment('商品-库存-记录');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stock_logs');
    }
};
