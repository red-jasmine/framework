<?php

namespace RedJasmine\Wallet\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Wallet\Domain\Data\WalletTransactionData;
use RedJasmine\Wallet\Domain\Data\WalletWithdrawalData;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Enums\WalletSystemAppEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Exceptions\WalletException;

class WalletWithdrawalService
{
    public function __construct(
        protected WalletService $walletService

    ) {
    }


    /**
     * @param  Wallet  $wallet
     * @param  WalletWithdrawalData  $data
     *
     *
     *
     * @return WalletWithdrawal
     * @throws WalletException
     */
    public function withdrawal(Wallet $wallet, WalletWithdrawalData $data) : WalletWithdrawal
    {

        // 构建 提现模型
        $withdrawal                     = WalletWithdrawal::make(['wallet_id' => $wallet->id]);
        $withdrawal->wallet_id          = $wallet->id;
        $withdrawal->owner              = $wallet->owner;
        $withdrawal->amount             = $data->amount;
        $withdrawal->withdrawal_time    = Carbon::now();
        $withdrawal->status             = WithdrawalStatusEnum::PROCESSING;
        $withdrawal->approval_status    = ApprovalStatusEnum::PROCESSING;
        $withdrawal->payee_channel      = $data->payee->channel;
        $withdrawal->payee_account_type = $data->payee->accountType;
        $withdrawal->payee_account_no   = $data->payee->accountNo;
        $withdrawal->payee_name         = $data->payee->name ?? null;
        $withdrawal->payee_cert_type    = $data->payee->certType ?? null;
        $withdrawal->payee_cert_no      = $data->payee->certNo ?? null;
        $withdrawal->setUniqueNo();
        // 钱包扣款
        $transactionData                  = new WalletTransactionData();
        $transactionData->appId           = WalletSystemAppEnum::WITHDRAWAL->value;
        $transactionData->outTradeNo      = $withdrawal->withdrawal_no;
        $transactionData->direction       = AmountDirectionEnum::EXPENSE;
        $transactionData->amount          = $data->amount;
        $transactionData->transactionType = TransactionTypeEnum::WITHDRAWAL;
        $this->walletService->transaction($wallet, $transactionData);


        return $withdrawal;
    }


}