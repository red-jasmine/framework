<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('order_product_card_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家类型');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('order_product_id')->comment('商品ID');
            $table->unsignedBigInteger('num')->default(1)->comment('数量');
            $table->text('content')->nullable()->comment('内容');
            $table->string('status')->nullable()->comment('状态');
            $table->json('extends')->nullable()->comment('扩展');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-卡密表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('order_product_card_keys');
    }
};
