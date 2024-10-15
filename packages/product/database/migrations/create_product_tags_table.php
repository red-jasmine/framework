<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') . 'product_tags', function (Blueprint $table) {
            $table->id();
            $table->morphs('owner', 'idx_owner');
            $table->string('name')->comment('标签');
            $table->string('description')->nullable()->comment('描述');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('color')->nullable()->comment('颜色');
            $table->string('cluster')->nullable()->comment('群簇');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_show')->default(false)->comment('是否展示');
            $table->boolean('is_public')->default(false)->comment('是否公共的');
            $table->string('status', 32)->comment('状态');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品标签');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') . 'product_tags');
    }
};
