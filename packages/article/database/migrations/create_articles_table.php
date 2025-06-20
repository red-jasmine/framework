<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Article\Domain\Models\Enums\ArticleStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->string('title')->comment('标题');
            $table->string('image')->nullable()->comment('图片');
            $table->string('description')->nullable()->comment('描述');
            $table->string('keywords')->nullable()->comment('关键字');
            $table->string('status')->comment(ArticleStatusEnum::comments('状态'));
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->boolean('is_show')->default(true)->comment('是否展示');
            $table->boolean('is_top')->default(false)->comment('是否置顶');
            $table->timestamp('publish_time')->nullable()->comment('发布时间');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->string('approval_status')->nullable()->comment('审批状态');

            $table->operator();
            $table->softDeletes();
            $table->comment('文章表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('articles');
    }
};
