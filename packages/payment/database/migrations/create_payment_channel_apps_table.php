<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_apps', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('owner');
            $table->unsignedBigInteger('channel_id')->comment('渠道ID');
            $table->string('channel_app_id')->comment('渠道应用ID');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户ID');
            $table->decimal('fee_rate')->default(0)->comment('费率');// ‰ // 根据支付产品不同不一样的费率
            $table->text('channel_public_key')->nullable()->comment('渠道公钥');
            $table->text('channel_app_public_key')->nullable()->comment('应用公钥');
            $table->text('channel_app_private_key')->nullable()->comment('应用私钥');
            $table->string('status')->comment(ChannelAppStatusEnum::comments('状态'));
            $table->string('app_name')->nullable()->comment('应用名称');
            $table->string('merchant_name')->nullable()->comment('商户名称');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator', 'idx_creator');
            $table->nullableMorphs('updater', 'idx_updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('支付渠道应用');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_apps');
    }
};
