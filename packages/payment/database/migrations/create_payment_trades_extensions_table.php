<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_trades_extensions',
            static function (Blueprint $table) {
                $table->id()->comment('表ID');
                $table->unsignedBigInteger('trade_id')->unique()->comment('交易表ID');
                $table->string('request_url')->nullable()->comment('请求地址');
                $table->string('return_url')->nullable()->comment('成功重定向地址');
                $table->string('notify_url')->nullable()->comment('业务通知地址');
                $table->json('pass_back_params')->nullable()->comment('回传参数');
                $table->json('good_details')->nullable()->comment('支付明细');
                $table->json('device')->nullable()->comment('设备信息');
                $table->json('client')->nullable()->comment('客户端信息');
                $table->json('expands')->nullable()->comment('扩展参数');
                $table->timestamps();
                $table->comment('支付-支付扩展信息表');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_trades_extensions');
    }
};
