<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix') . 'payment_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->comment('支付平台');
            $table->string('name')->comment('平台名称');
            $table->string('icon')->comment('Logo');
            $table->timestamps();
            $table->unique('platform', 'uk_platform');
            $table->comment('支付平台');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix') . 'payment_platforms');
    }
};
