<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_brands', function (Blueprint $table) {
            $table->category('商品-品牌');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_brands');
    }
};
