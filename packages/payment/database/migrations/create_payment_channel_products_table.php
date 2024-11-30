<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_products', function (Blueprint $table) {
            $table->id();
            $table->string('channel_code')->comment('渠道');
            $table->string('code')->comment('产品标识');
            $table->string('name')->comment('产品名称');
            $table->decimal('rate')->default(0)->comment('默认费率');
            $table->string('status')->comment(ChannelStatusEnum::comments('状态'));
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->unique([ 'channel_code', 'code' ], 'uk_channel_code');
            $table->comment('支付渠道支付产品');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_products');
    }
};
