<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'product_extend_group_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_group_id');
            $table->timestamps();
            $table->index('product_id', 'idx_product');
            $table->index('product_group_id', 'idx_product_group');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'product_extend_group_pivot');
    }
};
