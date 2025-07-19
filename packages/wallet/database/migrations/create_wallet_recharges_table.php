<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_recharges', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('recharge_no', 32)->unique()->comment('充值单号');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('wallet_type')->comment('钱包类型');
            $table->string('currency', 3)->comment('货币');
            $table->decimal('amount', 12)->comment('金额');
            $table->string('status')->comment(RechargeStatusEnum::comments('状态'));
            $table->timestamp('recharge_time')->nullable()->comment('充值时间');
            // 应付金额
            $table->decimal('exchange_rate', 10, 5)->comment('汇率');
            $table->string('payment_currency', 3)->comment('支付货币');
            $table->decimal('payment_amount', 12)->default(0)->comment('应付金额');
            $table->decimal('payment_fee', 12)->default(0)->comment('支付手续费');
            $table->decimal('total_payment_amount', 12)->default(0)->comment('总支付金额');
            $table->string('payment_status')->nullable()->comment(PaymentStatusEnum::comments('支付状态'));
            $table->timestamp('payment_time')->nullable()->comment('支付时间');
            // 支付类型
            $table->string('payment_type', 32)->nullable()->comment('支付单类型');
            $table->string('payment_id', 64)->nullable()->comment('支付单ID');
            $table->string('payment_channel_trade_no', '64')->nullable()->comment('支付渠道单号');
            $table->decimal('payment_channel_amount', 12)->nullable()->comment('支付渠道金额');
            $table->string('payment_mode', '32')->nullable()->comment('支付方式');
            $table->string('fail_reason')->nullable()->comment('失败原因');
            $table->json('extra')->nullable()->comment('扩展字段');
            $table->operator();
            $table->comment('钱包-充值单');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_recharges');
    }
};
