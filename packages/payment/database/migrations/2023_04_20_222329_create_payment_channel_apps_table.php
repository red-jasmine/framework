<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_channel_apps', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('owner_type', 20)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者UID');
            $table->string('owner_nickname', 64)->nullable()->comment('所属者昵称');
            $table->string('channel_type')->nullable()->comment('支付渠道');
            $table->string('channel_merchant_id')->nullable()->comment('渠道商户ID');
            $table->string('channel_sub_merchant_id', 50)->nullable()->comment('渠道子商户ID');
            $table->string('channel_app_id')->nullable()->comment('渠道应用ID');
            $table->string('channel_app_sub_id')->nullable()->comment('渠道子商户应用ID');
            $table->unsignedBigInteger('status')->default(1)->comment('状态');
            $table->text('channel_app_public_key')->nullable()->comment('应用公钥');
            $table->text('channel_app_private_key')->nullable()->comment('应用私钥');
            $table->text('channel_public_key')->nullable()->comment('渠道公钥');
            $table->string('modes')->nullable()->comment('支持模式');
            $table->timestamps();
            $table->comment('支付渠道');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_channel_apps');
    }
};
