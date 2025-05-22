<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up() : void
    {
        Schema::create('topic_categories', function (Blueprint $table) {
            $table->category('话题-分类');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('topic_categories');
    }
};
