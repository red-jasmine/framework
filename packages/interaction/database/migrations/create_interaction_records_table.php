<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('interaction_records', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('resource_type', 32)->comment('资源类型');
            $table->string('resource_id', 64)->comment('资源ID');
            $table->string('user_type', 32)->comment('用户类型');
            $table->string('user_id', 64)->comment('用户ID');
            $table->string('interaction_type', 64)->comment('互动类型');
            $table->unsignedBigInteger('quantity')->default(1)->comment('数量');
            $table->timestamp('interaction_time')->comment('互动时间');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('互动记录表');


            $table->index(['user_id', 'resource_id', 'interaction_type', 'user_type', 'resource_type',], 'idx_user_resource_interaction');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_records');
    }
};
