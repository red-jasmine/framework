<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_channel_products',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->string('channel_code')->comment('渠道');
                $table->string('type')->default(ChannelProductTypeEnum::PAYMENT->value)->comment(ChannelProductTypeEnum::comments('产品类型'));
                $table->string('code')->comment('产品标识');
                $table->string('name')->comment('产品名称');
                $table->string('status')->default(ChannelProductStatusEnum::ENABLE->value)->comment(ChannelProductStatusEnum::comments('状态'));
                $table->string('remarks')->nullable()->comment('备注');
                $table->string('gateway')->nullable()->comment('支付网关名称');
                $table->json('extra')->nullable()->comment('扩展');
                $table->operator();
                $table->softDeletes();
                $table->unique(['channel_code', 'code'], 'uk_channel_code');
                $table->comment('支付渠道支付产品');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_channel_products');
    }
};
