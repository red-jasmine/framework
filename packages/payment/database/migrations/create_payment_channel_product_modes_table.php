<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes', function (Blueprint $table) {
            $table->id();
            $table->string('channel_code')->comment('支付渠道');
            $table->string('product_code')->comment('支付产品');
            $table->string('method_code')->comment('支付方式');
            $table->string('platform_code')->comment('支付平台');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();

            $table->unique([ 'channel', 'product', 'platform', 'method' ], 'uk_channel_product_method_platform');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes');
    }
};
