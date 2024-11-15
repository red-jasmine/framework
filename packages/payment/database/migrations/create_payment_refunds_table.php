<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix') .'payment_refunds', function (Blueprint $table) {
            $table->id();

            $table->string('currency')->comment('货币');
            $table->decimal('amount')->default(0)->comment('金额');

            $table->timestamps();
            $table->comment('支付-退款');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment0000000000000000.tables.prefix') .'payment_refunds');
    }
};
