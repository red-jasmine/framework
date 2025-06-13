<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level')->unique()->default(0)->comment('等级');
            $table->string('name');
            $table->string('description')->nullable()->comment('描述');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('image')->nullable()->comment('图片');
            $table->json('upgrades')->nullable()->comment('升级条件');
            $table->json('keeps')->nullable()->comment('保级条件');
            $table->json('ratios')->nullable()->comment('佣金比例'); // TODO
            $table->operator();
            $table->comment('推广员等级');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_levels');
    }
};
