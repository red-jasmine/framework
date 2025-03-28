<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('products_extension', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            //
            $table->string('tips')->nullable()->comment('提示');
            // 售后服务
            $table->json('after_sales_services')->nullable()->comment('售后服务');
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

            $table->json('form')->nullable()->comment('表单');
            $table->json('tools')->nullable()->comment('工具');
            $table->json('extras')->nullable()->comment('扩展');
            $table->string('remarks')->nullable()->comment('备注');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-附加信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('products_extension');
    }
};
