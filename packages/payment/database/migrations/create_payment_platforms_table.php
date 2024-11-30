<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('标识');
            $table->string('name')->comment('平台名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('type')->nullable()->comment('平台类型');
            $table->string('remarks')->nullable()->comment('备注');
            $table->timestamps();
            $table->unique('code', 'uk_platform');
            $table->comment('支付平台');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_platforms');
    }
};
