<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('interaction_table', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('resource_type', 64)->comment('资源类型');
            $table->string('resource_id', 64)->comment('资源ID');
            // 数量
            $table->unsignedBigInteger('like_count')->default(0)->comment('点赞数量');
            $table->unsignedBigInteger('favorite_count')->default(0)->comment('收藏数量');
            $table->unsignedBigInteger('comment_count')->default(0)->comment('评论数量');
            $table->unsignedBigInteger('view_count')->default(0)->comment('浏览数量');
            $table->unsignedBigInteger('share_count')->default(0)->comment('分享数量');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_comments');
    }
};
