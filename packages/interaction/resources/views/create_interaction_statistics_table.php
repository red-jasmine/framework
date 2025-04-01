<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('interaction_statistics', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('id');
            $table->string('resource_type', 64)->comment('资源类型');
            $table->string('resource_id', 64)->comment('资源ID');
            $table->string('interaction_type', 64)->comment('互动类型');
            $table->unsignedBigInteger('quantity')->default(0)->comment('数量');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('interaction_statistics');
    }
};
