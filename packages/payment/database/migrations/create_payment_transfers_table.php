<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'payment_transfers',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('transfer_no')->unique()->comment('转账号');
                $table->string('batch_no')->nullable()->comment('批次号');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');

                $table->string('subject')->comment('标题');
                $table->string('description')->nullable()->comment('说明');
                $table->string('amount_currency',3)->comment('金额货币');
                $table->decimal('amount_amount')->default(0)->comment('金额值');
                $table->string('transfer_status')->comment('转账状态');
                // 渠道信息
                $table->string('method_code')->comment('支付方式');
                $table->string('scene_code')->comment(TransferSceneEnum::comments('转账场景'));
                $table->string('channel_batch_no')->nullable()->comment('渠道批次号');

                $table->unsignedBigInteger('system_channel_app_id')->nullable()->comment('系统内渠道应用ID');
                $table->string('channel_code')->nullable()->comment('支付渠道');
                $table->string('channel_merchant_id')->nullable()->comment('渠道商户号');
                $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
                $table->string('channel_product_code')->nullable()->comment('支付产品CODE');
                $table->string('channel_transfer_no')->nullable()->comment('渠道转账单号');

                // 收款方
                $table->string('payee_identity_type')->nullable()->comment('收款方身份标识类型');
                $table->string('payee_identity_id')->nullable()->comment('收款方身份标识ID');
                $table->string('payee_cert_type')->nullable()->comment('收款证件类型');
                $table->string('payee_cert_no')->nullable()->comment('收款方证件号');
                $table->string('payee_name')->nullable()->comment('收款方类型');

                $table->timestamp('executing_time')->nullable()->comment('执行时间');
                $table->timestamp('processing_time')->nullable()->comment('处理时间');
                $table->timestamp('transfer_time')->nullable()->comment('转账时间');
                $table->timestamp('finish_time')->nullable()->comment('结束时间');
                $table->operator();
                $table->comment('支付-转账');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'payment_transfers');
    }
};
