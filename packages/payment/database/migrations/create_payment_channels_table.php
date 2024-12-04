<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channels', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('code')->comment('渠道标识');
            $table->string('name')->comment('渠道名称');
            $table->string('status')->comment(ChannelStatusEnum::comments('状态'));
            $table->nullableMorphs('creator','idx_creator');
            $table->nullableMorphs('updater','idx_updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('支付渠道');
            $table->unique('code', 'uk_channel');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channels');
    }
};
