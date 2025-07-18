<?php

namespace RedJasmine\Wallet\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Wallet\Domain\Data\WalletTransactionData;
use RedJasmine\Wallet\Domain\Data\WalletWithdrawalData;
use RedJasmine\Wallet\Domain\Data\WalletWithdrawalPaymentData;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Enums\WalletSystemAppEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalPaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Exceptions\WalletException;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;

class WalletWithdrawalService
{
    public function __construct(
        protected WalletService $walletService,
        protected WalletRepositoryInterface $walletRepository
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
        $withdrawal->wallet_type        = $wallet->type;
        $withdrawal->owner              = $wallet->owner;
        $withdrawal->amount             = $data->amount;
        $withdrawal->withdrawal_time    = Carbon::now();
        $withdrawal->status             = WithdrawalStatusEnum::PROCESSING;
        $withdrawal->approval_status    = ApprovalStatusEnum::PENDING;
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
        $transactionData->outTradeNo      = $withdrawal->no;
        $transactionData->direction       = AmountDirectionEnum::EXPENSE;
        $transactionData->amount          = $data->amount;
        $transactionData->transactionType = TransactionTypeEnum::WITHDRAWAL;
        $this->walletService->transaction($wallet, $transactionData);

        return $withdrawal;
    }


    /**
     * @param  WalletWithdrawal  $withdrawal
     * @param  ApprovalStatusEnum  $approvalStatus
     * @param  string|null  $approvalMessage
     *
     * @return void
     * @throws WalletException
     * @throws WalletWithdrawalException
     */
    public function approval(WalletWithdrawal $withdrawal, ApprovalStatusEnum $approvalStatus, ?string $approvalMessage = null) : void
    {
        // 审批
        $withdrawal->approval($approvalStatus, $approvalMessage);

        switch ($approvalStatus) {
            case ApprovalStatusEnum::PASS:
                $withdrawal->paymentPrepare();
                break;
            case ApprovalStatusEnum::REJECT:
            case ApprovalStatusEnum::REVOKE:
                $this->refundWallet($withdrawal);
                $withdrawal->fail();
                break;
            case ApprovalStatusEnum::PENDING:

                throw new WalletWithdrawalException('To be implemented');
                break;

        }

    }

    protected function refundWallet(WalletWithdrawal $withdrawal) : bool
    {
        $wallet = $this->walletRepository->findLock($withdrawal->wallet_id);
        // 钱包扣款
        $transactionData                  = new WalletTransactionData();
        $transactionData->appId           = WalletSystemAppEnum::WITHDRAWAL->value;
        $transactionData->outTradeNo      = $withdrawal->no;
        $transactionData->direction       = AmountDirectionEnum::INCOME;
        $transactionData->amount          = $withdrawal->amount;
        $transactionData->transactionType = TransactionTypeEnum::REFUND;
        $this->walletService->transaction($wallet, $transactionData);
        $this->walletRepository->update($wallet);

        return true;
    }


    /**
     * @param  WalletWithdrawal  $withdrawal
     * @param  WalletWithdrawalPaymentData  $data
     *
     * @return void
     * @throws WalletWithdrawalException
     */
    public function payment(WalletWithdrawal $withdrawal, WalletWithdrawalPaymentData $data) : void
    {
        $withdrawal->paymentCallback($data);
        switch ($withdrawal->payment_status) {
            case WithdrawalPaymentStatusEnum::SUCCESS:
                $withdrawal->success();
                break;
            case WithdrawalPaymentStatusEnum::FAIL:
                $this->refundWallet($withdrawal);
                $withdrawal->fail();
                break;
            case WithdrawalPaymentStatusEnum::PAYING:
                break;
            case WithdrawalPaymentStatusEnum::PREPARE:
                throw new WalletWithdrawalException('To be implemented');
                // 无需处理
                break;

        }

    }
}