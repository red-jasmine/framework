<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('interaction_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('target_type', 64)->comment('资源类型');
            $table->string('target_id', 64)->comment('资源ID');
            $table->string('status', 32)->comment('状态');
            $table->unsignedBigInteger('root_id')->default(0)->comment('顶级ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->text('content')->nullable()->comment('内容');
            $table->boolean('is_top')->default(false)->comment('是否置顶');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');

            $table->string('commenter_type', 32)->nullable();
            $table->string('commenter_id', 64)->nullable();
            $table->string('commenter_nickname', 64)->nullable();
            $table->string('commenter_avatar')->nullable();


            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();

            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_comments');
    }
};
