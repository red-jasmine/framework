<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-card.tables.prefix','jasmine_').'card_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->string('name')->comment('分组名称');
            $table->string('remarks')->nullable()->comment('备注');
            $table->text('content_template')->nullable()->comment('内容模板');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->comment('卡密分组');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-card.tables.prefix','jasmine_').'card_groups');
    }
};
