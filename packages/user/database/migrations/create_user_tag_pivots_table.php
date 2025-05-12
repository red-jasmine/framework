<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'user_tag_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_tag_id');
            $table->timestamps();
            $table->index('user_id', 'idx_user');
            $table->index('user_tag_id', 'idx_user_tag');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'user_tag_pivot');
    }
};
