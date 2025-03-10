<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_apps',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->string('owner_type', 32);
                $table->string('owner_id', 64);
                $table->string('channel_code')->comment('渠道编码');
                $table->string('channel_app_id')->comment('渠道应用ID');
                $table->string('app_name')->nullable()->comment('应用名称');
                $table->string('channel_merchant_id')->comment('渠道商户号');
                $table->string('merchant_name')->nullable()->comment('商户名称');
                $table->boolean('is_sandbox')->default(false)->comment('是否沙箱');
                $table->string('icon')->nullable()->comment('应用图标');
                $table->string('sign_method')->nullable()->comment(SignMethodEnum::comments('加签方式'));
                $table->string('sign_type')->nullable()->comment('接口加签算法');
                $table->string('encrypt_type')->nullable()->comment('内容加密算法');
                $table->text('encrypt_key')->nullable()->comment('内容加密密钥');
                $table->text('channel_app_secret')->nullable()->comment('渠道应用密钥');
                $table->text('channel_public_key')->nullable()->comment('渠道公钥');
                $table->text('channel_app_public_key')->nullable()->comment('应用公钥');
                $table->text('channel_app_private_key')->nullable()->comment('应用私钥');
                $table->text('channel_root_cert')->nullable()->comment('渠道根证书');
                $table->text('channel_public_key_cert')->nullable()->comment('渠道公钥证书');
                $table->text('channel_app_public_key_cert')->nullable()->comment('应用公钥证书');
                $table->string('status')->default(ChannelAppStatusEnum::ENABLE->value)->comment(ChannelAppStatusEnum::comments('状态'));
                $table->string('remarks')->nullable()->comment('备注');
                $table->json('extensions')->nullable()->comment('扩展');
                $table->string('creator_type', 32)->nullable();
                $table->string('creator_id', 64)->nullable();
                $table->string('updater_type', 32)->nullable();
                $table->string('updater_id', 64)->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index([ 'channel_code', 'channel_app_id' ], 'idx_channel_app');
                $table->comment('支付渠道应用');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_apps');
    }
};
