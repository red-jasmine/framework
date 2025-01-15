<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelMerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_channel_merchants',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->string('owner_type', 32);
                $table->string('owner_id', 64);
                $table->string('channel_code')->comment('渠道编码');
                $table->string('type')->default(MerchantTypeEnum::GENERAL->value)->comment(MerchantTypeEnum::comments('类型'));
                $table->string('channel_merchant_id')->comment('渠道商户号');
                $table->string('channel_merchant_name')->comment('商户名称');
                $table->boolean('is_sandbox')->default(false)->comment('是否沙箱');
                $table->string('status')->default(ChannelMerchantStatusEnum::ENABLE->value)->comment(ChannelMerchantStatusEnum::comments('状态'));
                $table->string('remarks')->nullable()->comment('备注');
                $table->string('creator_type', 32)->nullable();
                $table->string('creator_id', 64)->nullable();
                $table->string('updater_type', 32)->nullable();
                $table->string('updater_id', 64)->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->comment('支付渠道商户表');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_channel_merchants');
    }
};
