<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_channel_product_id')->comment('支付产品ID');
            $table->string('scene_code')->comment('支付场景');
            $table->string('method_code')->comment('支付方式');
            $table->string('status')->default(ModeStatusEnum::ENABLE->value)->comment(ModeStatusEnum::comments('状态'));
            $table->nullableMorphs('creator', 'idx_creator');
            $table->nullableMorphs('updater', 'idx_updater');
            $table->timestamps();
            $table->unique([ 'payment_channel_product_id', 'scene_code', 'method_code' ], 'uk_method');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes');
    }
};
