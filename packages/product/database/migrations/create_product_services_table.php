<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') . 'product_services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('color')->nullable()->comment('颜色');
            $table->string('cluster')->nullable()->comment('群簇');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_show')->default(true)->comment('是否展示');
            $table->string('status', 32)->comment('状态');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品服务');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') . 'product_services');
    }
};
