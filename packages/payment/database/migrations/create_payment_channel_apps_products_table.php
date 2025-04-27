<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_channel_app_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_channel_app_id')->comment('渠道App ID');
            $table->unsignedBigInteger('system_channel_product_id')->comment('支付产品 ID');
            $table->timestamps();
            $table->comment('支付渠道应用产品关联表');
            $table->unique([ 'system_channel_app_id', 'system_channel_product_id' ], 'uk_channel_product');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'payment_channel_app_products');
    }
};
