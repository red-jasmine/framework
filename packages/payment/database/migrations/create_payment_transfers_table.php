<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_transfers',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('表ID');
                $table->string('transfer_no')->unique()->comment('转账号');
                $table->string('batch_no')->nullable()->comment('批次号');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');

                $table->string('subject')->comment('标题');
                $table->string('description')->nullable()->comment('说明');
                $table->string('amount_currency')->comment('金额货币');
                $table->bigInteger('amount_value')->default(0)->comment('金额值');
                $table->string('transfer_status')->comment('转账状态');
                // 渠道信息
                $table->string('channel_batch_no')->nullable()->comment('渠道批次号');
                $table->unsignedBigInteger('payment_channel_app_id')->nullable()->comment('系统内渠道应用ID');
                $table->string('channel_code')->nullable()->comment('支付渠道');
                $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
                $table->string('channel_product_code')->nullable()->comment('支付产品CODE');
                $table->string('scene_code')->comment('场景');

                // 收款方
                $table->string('payee_identity_type')->nullable()->comment('收款方身份标识类型');
                $table->string('payee_identity_id')->nullable()->comment('收款方身份标识ID');
                $table->string('payee_cert_type')->nullable()->comment('收款证件类型');
                $table->string('payee_cert_no')->nullable()->comment('收款方证件号');
                $table->string('payee_name')->nullable()->comment('收款方类型');


                $table->string('creator_type', 32)->nullable();
                $table->string('creator_id', 64)->nullable();
                $table->string('updater_type', 32)->nullable();
                $table->string('updater_id', 64)->nullable();
                $table->timestamps();
                $table->comment('支付-转账');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_transfers');
    }
};
