<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_series', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('owner_type',64);
            $table->string('owner_id',64);
            $table->string('name')->comment('系列名称');
            $table->string('remarks')->nullable()->comment('备注');
            $table->operator();
            $table->comment('商品-系列表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_series');
    }
};
