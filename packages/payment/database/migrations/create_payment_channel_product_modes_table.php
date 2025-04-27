<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'payment_channel_product_modes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_channel_product_id')->comment('支付产品ID');
            $table->string('scene_code')->comment('支付场景');
            $table->string('method_code')->comment('支付方式');
            $table->string('status')->default(ModeStatusEnum::ENABLE->value)->comment(ModeStatusEnum::comments('状态'));
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->unique([ 'system_channel_product_id', 'scene_code', 'method_code' ], 'uk_method');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'payment_channel_product_modes');
    }
};
