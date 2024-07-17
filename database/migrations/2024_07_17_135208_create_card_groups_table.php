<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('card_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->morphs('owner');
            $table->string('name')->comment('分组名称');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->comment('卡密分组');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('card_groups');
    }
};
