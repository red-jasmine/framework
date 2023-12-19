<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->morphs('owner');
            $table->morphs('product');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('规格ID');
            $table->unsignedBigInteger('batch_no')->default(0)->comment('批次号');
            $table->unsignedTinyInteger('stock_type')->default(1)->comment('库存类型');
            $table->unsignedBigInteger('stock')->default(1)->comment('库存');
            $table->unsignedBigInteger('sales')->default(0)->comment('销量');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->text('content')->comment('内容');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('卡密表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('cards');
    }
};
