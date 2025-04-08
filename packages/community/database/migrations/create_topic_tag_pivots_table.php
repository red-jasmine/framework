<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('topic_tag_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('topic_tag_id');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('topic_tag_pivots');
    }
};
