<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up() : void
    {
        Schema::create('article_categories', function (Blueprint $table) {

            $table->string('owner_type', 64);
            $table->string('owner_id', 64);

            $table->category('文章-分类');


        });
    }

    public function down() : void
    {
        Schema::dropIfExists('article_categories');
    }
};
