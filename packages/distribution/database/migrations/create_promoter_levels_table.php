<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level')->unique()->default(0)->comment('等级');
            $table->string('name');
            $table->string('description')->nullable()->comment('描述');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('image')->nullable()->comment('图片');
            $table->unsignedTinyInteger('product_ratio')->default(0)->comment('产品佣金比例');
            $table->unsignedTinyInteger('parent_ratio')->default(0)->comment('上级佣金比例');
            $table->json('upgrades')->nullable()->comment('升级条件');
            $table->json('keeps')->nullable()->comment('保级条件');
            $table->json('benefits')->nullable()->comment('权益');
            // 申请类型 关闭申请、自动申请、手动申请
            // 审核类型：字段审核、手动审核
            //
            $table->operator();
            $table->comment('推广员等级');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_levels');
    }
};
