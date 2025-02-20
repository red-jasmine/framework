<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_').'order_products_extension', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->json('after_sales_services')->nullable()->comment('售后服务');
            $table->text('seller_remarks')->nullable()->comment('卖家备注');
            $table->text('buyer_remarks')->nullable()->comment('买家备注');
            $table->string('seller_message')->nullable()->comment('卖家留言');
            $table->string('buyer_message')->nullable()->comment('买家留言');
            $table->json('form')->nullable()->comment('表单');
            $table->json('tools')->nullable()->comment('商品工具');
            $table->json('seller_extras')->nullable()->comment('卖家扩展信息');
            $table->json('buyer_extras')->nullable()->comment('买家扩展信息');
            $table->json('other_extras')->nullable()->comment('其他扩展信息');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单商品-附加信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_').'order_products_extension');
    }
};
