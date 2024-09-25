<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            //
            $table->string('tips')->nullable()->comment('提示');
            $table->json('promise_services')->nullable()->comment('承诺服务');
            // 属性
            $table->json('basic_props')->nullable()->comment('基本属性');
            $table->json('sale_props')->nullable()->comment('销售属性');
            $table->json('customize_props')->nullable()->comment('自定义属性');
            // SEO
            $table->string('keywords')->nullable()->comment('关键字');
            $table->string('description')->nullable()->comment('描述');
            // 内容
            $table->json('images')->nullable()->comment('图片集');
            $table->json('videos')->nullable()->comment('视频集');
            $table->longText('detail')->nullable()->comment('详情');
            // 物品基本信息

            $table->string('weight')->nullable()->comment('重:kg');
            $table->string('width')->nullable()->comment('宽:m');
            $table->string('height')->nullable()->comment('高:m');
            $table->string('length')->nullable()->comment('长:m');
            $table->string('size')->nullable()->comment('体积:m³');
            $table->string('remarks')->nullable()->comment('备注');

            $table->json('tools')->nullable()->comment('工具');
            $table->json('expands')->nullable()->comment('扩展');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-附加信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_infos');
    }
};
