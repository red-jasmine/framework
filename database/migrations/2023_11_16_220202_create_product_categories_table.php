<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Category\Enums\CategoryStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('类目ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级类目');
            $table->string('name')->comment('类目名称');
            $table->string('image')->nullable()->comment('图片');
            $table->string('group_name')->nullable()->comment('分组');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('is_leaf')->default(0)->comment('是否叶子类目');
            $table->unsignedTinyInteger('is_show')->default(0)->comment('是否展示');
            $table->string('status', 32)->comment(CategoryStatusEnum::comments('状态'));
            $table->json('expands')->nullable()->comment('扩展信息');

            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-类目表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
};
