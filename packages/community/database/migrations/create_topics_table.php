<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->string('title')->comment('标题');
            $table->string('image')->nullable()->comment('图片');
            $table->string('description')->nullable()->comment('描述');
            $table->string('keywords')->nullable()->comment('关键字');
            $table->string('status')->comment(ArticleStatusEnum::comments('状态'));
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->boolean('is_top')->default(false)->comment('是否置顶');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->string('approval_status')->nullable()->comment('审批状态');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('creator_avatar', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('creator_avatar', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('话题表');

            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('topics');
    }
};
