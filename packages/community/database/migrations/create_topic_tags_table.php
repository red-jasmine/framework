<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('topic_tags', function (Blueprint $table) {
            $table->category('标签');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('topic_tags');
    }
};
