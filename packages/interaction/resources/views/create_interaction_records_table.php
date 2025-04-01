<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('interaction_records', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('id');
            $table->string('resource_type', 32)->comment('资源类型');
            $table->string('resource_id', 64)->comment('资源ID');
            $table->string('interaction_type', 32)->comment('互动类型');
            $table->string('user_type', 32)->comment('用户类型');
            $table->string('user_id', 64)->comment('用户ID');
            $table->timestamp('interaction_time')->comment('互动时间');
            $table->unsignedBigInteger('quantity')->default(0)->comment('数量');
            $table->tinyInteger('star')->default(0)->comment('互相指数');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->unsignedBigInteger('root_id')->default(0)->comment('顶级ID');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('互动记录');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_records');
    }
};
