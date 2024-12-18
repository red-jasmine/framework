<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\StatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_merchant_channel_app_permissions', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->unsignedBigInteger('merchant_id')->comment('商户ID');
            $table->unsignedBigInteger('channel_app_id')->comment('渠道应用表ID');
            $table->string('status')->default(PermissionStatusEnum::ENABLE->value)->comment(PermissionStatusEnum::comments('状态'));
            $table->nullableMorphs('creator', 'idx_creator');
            $table->nullableMorphs('updater', 'idx_updater');
            $table->timestamps();
            $table->comment('商户-支付渠道应用授权表');
            $table->unique([ 'merchant_id', 'channel_app_id' ], 'uk_merchant_channel_app');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_merchant_channel_app_permissions');
    }
};
