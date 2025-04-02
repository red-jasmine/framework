<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('interaction_record_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('resource_type', 32)->comment('资源类型');
            $table->string('resource_id', 64)->comment('资源ID');
            $table->string('user_type', 32)->comment('用户类型');
            $table->string('user_id', 64)->comment('用户ID');
            $table->string('interaction_type', 64)->comment('互动类型');
            $table->unsignedBigInteger('quantity')->default(1)->comment('数量');
            $table->timestamp('interaction_time')->comment('互动时间');

            $table->string('user_nickname', 64)->nullable();
            $table->string('user_avatar')->nullable();
            $table->text('content')->nullable()->comment('内容');
            $table->unsignedBigInteger('root_id')->default(0)->comment('顶级ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_top')->default(false)->comment('是否置顶');
            $table->boolean('is_hot')->default(false)->comment('是否热评');
            $table->boolean('is_good')->default(false)->comment('是否好评');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('互动记录-评论表');

            $table->index(['user_id', 'resource_id', 'interaction_type', 'user_type', 'resource_type',], 'idx_user_resource_interaction');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_record_comments');
    }
};
