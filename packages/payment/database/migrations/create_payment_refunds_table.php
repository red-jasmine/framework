<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') .'payment_refunds', function (Blueprint $table) {
            $table->id();



            $table->timestamps();
            $table->comment('支付-退款');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') .'payment_refunds');
    }
};
