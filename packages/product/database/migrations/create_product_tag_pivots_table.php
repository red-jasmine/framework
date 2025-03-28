<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'product_tag_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_tag_id');
            $table->timestamps();
            $table->index('product_id', 'idx_product');
            $table->index('product_tag_id', 'idx_product_tag');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'product_tag_pivot');
    }
};
