<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalPaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_withdrawals', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('withdrawal_no', 64)->unique()->comment('提现单号');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->string('amount_currency', 3)->comment('货币');
            $table->decimal('amount_total', 12)->comment('金额');
            $table->decimal('fee', 12)->default(0)->comment('费用');
            $table->string('status')->comment(WithdrawalStatusEnum::comments('提现状态'));
            $table->timestamp('withdrawal_time')->nullable()->comment('提现时间');

            $table->string('approval_status')->nullable()->comment(ApprovalStatusEnum::comments('审批状态'));
            $table->timestamp('approval_time')->nullable()->comment('审批时间');
            $table->string('approval_message')->nullable()->comment('审批信息');
            // 收款方
            $table->string('payee_channel')->comment('收款渠道');
            $table->string('payee_account_type')->comment('收款人账户类型');
            $table->text('payee_account_no')->comment('收款人账户');
            $table->text('payee_name')->nullable()->comment('收款人名称');
            $table->string('payee_cert_type')->nullable()->comment('证件类型');
            $table->text('payee_cert_no')->nullable()->comment('证件号');

            $table->string('payment_status', 32)->nullable()->comment(WithdrawalPaymentStatusEnum::comments('支付状态'));
            $table->string('payment_type', 32)->nullable()->comment('支付单类型');
            $table->string('payment_id', 64)->nullable()->comment('支付单ID');
            $table->string('payment_channel_trade_no', 64)->nullable()->comment('支付渠道单号');
            $table->timestamp('payment_time')->nullable()->comment('支付时间');
            $table->json('extras')->nullable()->comment('扩展字段');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();

            $table->comment('钱包-提现表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_withdrawals');
    }
};
