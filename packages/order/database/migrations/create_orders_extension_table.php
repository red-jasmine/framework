<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('orders_extension', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->json('discount_details')->nullable()->comment('优惠明细');
            $table->text('buyer_remarks')->nullable()->comment('买家备注');
            $table->text('seller_remarks')->nullable()->comment('卖家备注');
            $table->string('seller_message')->nullable()->comment('卖家留言');
            $table->string('buyer_message')->nullable()->comment('买家留言');
            $table->json('seller_extra')->nullable()->comment('卖家扩展信息');
            $table->json('buyer_extra')->nullable()->comment('买家扩展信息');
            $table->json('other_extra')->nullable()->comment('其他扩展信息');
            $table->json('tools')->nullable()->comment('订单工具');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-附加信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('orders_extension');
    }
};
