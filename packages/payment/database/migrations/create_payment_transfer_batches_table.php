<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_transfer_batches',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('batch_no')->unique()->comment('批次号');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
                $table->string('channel_code')->nullable()->comment('支付渠道');
                $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
                $table->string('channel_product_code')->nullable()->comment('支付产品CODE');
                $table->string('scene_code')->comment('场景');
                $table->string('channel_batch_no')->nullable()->comment('渠道批次号');
                $table->string('status')->comment('状态');
                $table->string('title')->comment('标题');
                $table->string('total_amount_currency')->default(0)->comment('金额货币');
                $table->unsignedBigInteger('total_amount_value')->default(0)->comment('总金额');
                $table->unsignedBigInteger('total_count')->default(0)->comment('总笔数');
                $table->operator();

                $table->comment('转账批次表');

            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_transfer_batches');
    }
};
