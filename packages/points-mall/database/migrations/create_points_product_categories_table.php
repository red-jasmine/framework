<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('points_product_categories', function (Blueprint $table) {

            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');

            $table->category('积分商品分类');


        });
    }

    public function down() : void
    {
        Schema::dropIfExists('points_product_categories');
    }
};
