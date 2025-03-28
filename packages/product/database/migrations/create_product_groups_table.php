<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'product_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('分组ID');
            $table->string('owner_type',64);
            $table->string('owner_id',64);
            $table->string('name')->comment('分组名称');
            $table->string('cluster')->nullable()->comment('群簇');
            $table->string('description')->nullable()->comment('描述');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级分组');
            $table->string('image')->nullable()->comment('图片');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_leaf')->default(false)->comment('是否叶子');
            $table->boolean('is_show')->default(false)->comment('是否展示');
            $table->string('status', 32)->comment(CategoryStatusEnum::comments('状态'));
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-分组表');
            $table->index('parent_id', 'idx_parent');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'product_groups');
    }
};
