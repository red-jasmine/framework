<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_transfer_extensions',
            static function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->unsignedBigInteger('transfer_id')->unique()->comment('转账表ID');
                $table->string('request_url')->nullable()->comment('请求地址');
                $table->string('notify_url')->nullable()->comment('业务通知地址');
                $table->json('pass_back_params')->nullable()->comment('回传参数');
                $table->json('extras')->nullable()->comment('扩展参数');
                $table->string('error_message')->nullable()->comment('错误信息');
                $table->timestamps();
                $table->comment('支付-转账扩展信息表');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_transfer_extensions');
    }
};
