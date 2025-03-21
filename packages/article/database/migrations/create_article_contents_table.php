<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('article_contents', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->unsignedBigInteger('article_id')->comment('文章ID');
            $table->string('content_type')->comment('内容类型');
            $table->longText('content')->comment('内容');
            $table->unsignedBigInteger('version')->default(1)->comment('版本');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('article_contents');
    }
};
