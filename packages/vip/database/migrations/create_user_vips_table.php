<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_vips', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->string('app_id', 32)->comment('应用ID');
            $table->string('type', 32)->comment('类型');
            $table->tinyInteger('level')->default(0)->comment('等级');
            $table->timestamp('start_time')->comment('生效时间');
            $table->timestamp('end_time')->comment('失效时间');
            $table->boolean('is_forever')->default(false)->comment('是否永久');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->unique(['owner_type', 'owner_id', 'app_id', 'type'], 'owner_app_vip');
            $table->comment('用户 VIP表');
        });
    }
    public function down()
    {
        Schema::dropIfExists('user_vips');
    }

};
