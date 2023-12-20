<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('order_info', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();

            $table->string('seller_remarks')->nullable()->comment('卖家备注');
            $table->string('seller_message')->nullable()->comment('卖家留言');
            $table->string('buyer_remarks')->nullable()->comment('买家备注');
            $table->string('buyer_message')->nullable()->comment('买家留言');
            $table->json('seller_extends')->nullable()->comment('卖家扩展信息');
            $table->json('buyer_extends')->nullable()->comment('买家扩展信息');
            $table->json('other_extends')->nullable()->comment('其他扩展信息');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-附加信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('order_info');
    }
};
