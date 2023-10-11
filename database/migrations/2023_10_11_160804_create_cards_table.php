<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type', 20)->comment('所属者类型');
            $table->string('owner_uid', 64)->comment('所属者UID');
            $table->string('owner_nickname', 64)->nullable()->comment('所属者昵称');

            $table->string('item_type')->default('')->comment('商品类型');
            $table->unsignedBigInteger('item_id')->default(0)->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('规格ID');

            $table->unsignedTinyInteger('batch')->default(0)->comment('批次号');
            $table->text('card')->comment('卡密');

            $table->unsignedTinyInteger('quantity')->default(0)->comment('数量');
            $table->unsignedTinyInteger('sold')->default(0)->comment('销量');

            $table->unsignedTinyInteger('status')->default(0)->comment('商品状态');
            $table->string('remark')->default()->comment('备注');

            $table->string('creator_type', 20)->nullable()->comment('创建者类型');
            $table->string('creator_uid', 64)->nullable()->comment('创建者ID');
            $table->string('creator_nickname', 64)->nullable()->comment('创建者昵称');

            $table->string('updater_type', 20)->nullable()->comment('更新者类型');
            $table->string('updater_uid', 64)->nullable()->comment('更新者UID');
            $table->string('updater_nickname', 64)->nullable()->comment('更新者昵称');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('卡密表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('cards');
    }
};
