<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-support.tables.prefix','jasmine_') .'wallet_withdrawals', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->decimal('amount', 12)->default(0)->comment('金额');
            $table->decimal('fee', 12)->default(0)->comment('费用');
            $table->string('status')->comment(WithdrawalStatusEnum::comments('提现状态'));
            $table->decimal('pay_amount', 12)->default(0)->comment('支付金额');

            $table->string('transfer_type')->comment('转账类型');
            $table->string('transfer_account')->comment('转账账户');
            $table->string('transfer_account_real_name')->nullable()->comment('账户实名');

            $table->string('payment_status')->nullable()->comment('支付状态');
            $table->string('payment_type')->nullable()->comment('支付单类型');
            $table->unsignedBigInteger('payment_id')->nullable()->comment('支付单ID');
            $table->string('payment_channel_trade_no', '64')->nullable()->comment('支付渠道单号');
            $table->timestamp('payment_time')->nullable()->comment('支付时间');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->json('extends')->nullable()->comment('扩展字段');
            $table->timestamps();
            $table->comment('钱包-提现单');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-support.tables.prefix','jasmine_') .'wallet_withdrawals');
    }
};
