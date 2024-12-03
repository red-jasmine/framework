<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_channel_product_id')->comment('支付产品ID');
            $table->string('method_code')->comment('支付方式');
            $table->string('platform_code')->comment('支付平台');
            $table->nullableMorphs('creator','idx_creator');
            $table->nullableMorphs('updater','idx_updater');
            $table->timestamps();
            $table->unique(['payment_channel_product_id', 'method_code', 'platform_code' ], 'uk_platform');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes');
    }
};
