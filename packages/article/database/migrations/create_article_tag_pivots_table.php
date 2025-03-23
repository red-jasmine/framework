<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('article_tag_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('article_tag_id');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('article_tag_pivots');
    }
};
