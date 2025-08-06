<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\SettleStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_settles',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('biz')->comment('业务标识');
                $table->string('settle_no', 64)->unique()->comment('结算号');
                $table->string('trade_no', 64)->comment('交易号');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->unsignedBigInteger('merchant_app_id')->comment('应用ID');
                $table->string('merchant_settle_no', 64)->comment('商户结算号');

                // 统计使用
                $table->string('subject', 64)->nullable()->comment('交易标题');
                $table->string('description', 64)->nullable()->comment('说明');
                $table->string('amount_currency', 3)->comment('货币');
                $table->decimal('amount_amount')->default(0)->comment('金额');

                $table->unsignedBigInteger('system_channel_app_id')->nullable()->comment('系统内渠道应用ID');
                $table->string('channel_code', 64)->nullable()->comment('支付渠道');
                $table->string('channel_merchant_id', 64)->nullable()->comment('渠道商户号');
                $table->string('channel_app_id', 64)->nullable()->comment('渠道应用ID');
                $table->string('channel_product_code', 64)->nullable()->comment('支付产品CODE');
                $table->string('channel_trade_no', 64)->comment('渠道交易单号');
                $table->string('channel_settle_no', 64)->nullable()->comment('渠道结算单号');
                $table->string('settle_status', 64)->comment(SettleStatusEnum::comments('结算状态'));

                $table->timestamp('create_time')->nullable()->comment('创建时间');
                $table->timestamp('settle_time')->nullable()->comment('结算时间');


                $table->operator();
                $table->comment('支付-结算单');


                $table->unique(['merchant_app_id', 'merchant_settle_no'], 'uk_merchant_settle_no');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_settles');
    }
};
