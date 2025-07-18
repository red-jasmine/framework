<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'payment_channels', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('code')->comment('渠道标识');
            $table->string('name')->comment('渠道名称');
            $table->string('status')->default(ChannelStatusEnum::ENABLE->value)->comment(ChannelStatusEnum::comments('状态'));
            $table->json('extra')->nullable()->comment('扩展');
            $table->operator();
            $table->softDeletes();
            $table->comment('支付渠道');
            $table->unique('code', 'uk_channel');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'payment_channels');
    }
};
