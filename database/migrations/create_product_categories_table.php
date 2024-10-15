<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') .'product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('类目ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级');
            $table->string('name')->comment('类目名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('image')->nullable()->comment('图片');
            $table->string('cluster')->nullable()->comment('群簇');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_leaf')->default(false)->comment('是否叶子');
            $table->boolean('is_show')->default(false)->comment('是否展示');
            $table->string('status', 32)->comment(CategoryStatusEnum::comments('状态'));
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-类目表');
            $table->index('parent_id','idx_parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') .'product_categories');
    }
};
