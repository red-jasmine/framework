<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_refunds_extension',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('refund_id')->comment('退款ID');
                $table->string('notify_url')->nullable()->comment('业务通知地址');
                $table->json('pass_back_params')->nullable()->comment('回传参数');
                $table->json('good_details')->nullable()->comment('支付明细');
                $table->json('device')->nullable()->comment('设备信息');
                $table->json('client')->nullable()->comment('客户端信息');
                $table->json('extras')->nullable()->comment('扩展参数');
                $table->string('error_message')->nullable()->comment('错误信息');
                $table->timestamps();
                $table->unique('refund_id', 'uk_refund');
                $table->comment('支付-退款扩展信息表');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_refunds_extension');
    }
};
