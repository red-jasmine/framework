<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_recharges', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('recharge_no', 64)->unique()->comment('充值单号');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->string('wallet_type')->comment('钱包类型');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('amount_currency', 3)->comment('货币');
            $table->decimal('amount_total', 12)->comment('金额');
            $table->decimal('fee', 12)->default(0)->comment('费用');
            $table->string('status')->comment(RechargeStatusEnum::comments('状态'));
            $table->decimal('pay_amount', 12)->default(0)->comment('支付金额');
            // 支付类型
            $table->string('payment_type')->nullable()->comment('支付单类型');
            $table->unsignedBigInteger('payment_id')->nullable()->comment('支付单ID');
            $table->string('payment_channel_trade_no', '64')->nullable()->comment('支付渠道单号');
            $table->string('payment_mode', '32')->nullable()->comment('支付方式');
            $table->timestamp('payment_time')->nullable()->comment('支付时间');
            $table->json('extra')->nullable()->comment('扩展字段');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->comment('钱包-充值单');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_recharges');
    }
};
