<?php

namespace RedJasmine\Wallet\Domain\Services;

use Cknow\Money\Money;
use Illuminate\Support\Carbon;
use Money\Currency;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Exceptions\ApprovalException;
use RedJasmine\Wallet\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Wallet\Domain\Data\Config\ExchangeCurrencyConfigData;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTransferData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletTransferData;
use RedJasmine\Wallet\Domain\Data\WalletTransactionData;
use RedJasmine\Wallet\Domain\Data\WalletWithdrawalData;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Enums\WalletSystemAppEnum;
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
        protected WalletRepositoryInterface $walletRepository,
        protected PaymentServiceInterface $paymentService,
    ) {
    }


    /**
     * @param  Wallet  $wallet
     * @param  WalletWithdrawalData  $data
     *
     *
     *
     * @return WalletWithdrawal
     * @throws WalletException|ApprovalException
     */
    public function withdrawal(Wallet $wallet, WalletWithdrawalData $data) : WalletWithdrawal
    {
        $amount = $data->amount->absolute();

        $walletConfig = $this->walletService->getWalletConfig($wallet->type);

        // 判断是否允许充值
        if (!$walletConfig->withdrawal || !$walletConfig->withdrawal->state) {
            throw new WalletException('不支持提现');
        }
        // 获取钱包配置
        $currencies = collect($walletConfig->withdrawal->currencies)->keyBy('currency')->all();
        /**
         * @var ExchangeCurrencyConfigData $currencyConfig
         */
        $currencyConfig = $currencies[$data->currency];

        // 计算支付金额

        $paymentAmount = Money::parse($amount->multiply($currencyConfig->exchangeRate)->getAmount(),
            new Currency($currencyConfig->currency)
        );
        // 计算手续费
        $paymentFee = $paymentAmount->multiply($currencyConfig->feeRate)->absolute();
        // 计算总支付金额
        $totalPaymentAmount = $paymentAmount->subtract($paymentFee);


        // 构建 提现模型
        $withdrawal                       = WalletWithdrawal::make(['wallet_id' => $wallet->id]);
        $withdrawal->wallet_id            = $wallet->id;
        $withdrawal->wallet_type          = $wallet->type;
        $withdrawal->owner                = $wallet->owner;
        $withdrawal->amount               = $data->amount;
        $withdrawal->withdrawal_time      = Carbon::now();
        $withdrawal->status               = WithdrawalStatusEnum::PROCESSING;
        $withdrawal->exchange_rate        = $currencyConfig->exchangeRate;
        $withdrawal->payment_amount       = $paymentAmount;
        $withdrawal->payment_fee          = $paymentFee;
        $withdrawal->total_payment_amount = $totalPaymentAmount;
        $withdrawal->payee_channel        = $data->payee->channel;
        $withdrawal->payee_account_type   = $data->payee->accountType;
        $withdrawal->payee_account_no     = $data->payee->accountNo;
        $withdrawal->payee_name           = $data->payee->name ?? null;
        $withdrawal->payee_cert_type      = $data->payee->certType ?? null;
        $withdrawal->payee_cert_no        = $data->payee->certNo ?? null;
        $withdrawal->payment_status       = null;

        $withdrawal->setUniqueNo();
        // 钱包扣款
        $transactionData                  = new WalletTransactionData();
        $transactionData->appId           = WalletSystemAppEnum::WITHDRAWAL->value;
        $transactionData->outTradeNo      = $withdrawal->withdrawal_no;
        $transactionData->direction       = AmountDirectionEnum::FROZEN; // 冻结
        $transactionData->amount          = $data->amount;
        $transactionData->transactionType = TransactionTypeEnum::WITHDRAWAL;
        $this->walletService->transaction($wallet, $transactionData);


        // 提交审批
        $withdrawal->submitApproval();

        return $withdrawal;
    }


    /**
     * 审批完成
     *
     * @param  WalletWithdrawal  $withdrawal
     * @param  ApprovalData  $approvalData
     *
     * @return void
     * @throws WalletWithdrawalException
     * @throws ApprovalException
     */
    public function approval(WalletWithdrawal $withdrawal, ApprovalData $approvalData) : void
    {
        // 审批
        $withdrawal->handleApproval($approvalData);


        switch ($approvalData->status) {
            case ApprovalStatusEnum::PASS:
                break;
            case ApprovalStatusEnum::REJECT:
            case ApprovalStatusEnum::REVOKE:
                // TODO 这里是否也应该异步处理？
                $this->refundWallet($withdrawal);
                break;
            case ApprovalStatusEnum::PENDING:

                throw new WalletWithdrawalException('To be implemented');
                break;

        }

    }


    /**
     * 创建转账
     *
     * @param  WalletWithdrawal  $withdrawal
     *
     * @return void
     * @throws WalletWithdrawalException
     */
    public function createTransfer(WalletWithdrawal $withdrawal) : void
    {
        // 判断是否允许创建转账
        if (!$withdrawal->canTransferPrepare()) {
            throw new WalletWithdrawalException('不支持创建转账');
        }
        $walletTransferData             = new WalletTransferData();
        $walletTransferData->businessNo = $withdrawal->getUniqueNo();
        $walletTransferData->payee      = $withdrawal->payee;
        $walletTransferData->amount     = $withdrawal->total_payment_amount;
        // 创建转账单
        $paymentTransferData = $this->paymentService->createTransfer($walletTransferData);
        $this->handleTransferCallback($withdrawal, $paymentTransferData);

    }


    protected function refundWallet(WalletWithdrawal $withdrawal) : bool
    {
        $wallet = $this->walletRepository->findLock($withdrawal->wallet_id);
        // 钱包扣款
        $transactionData                  = new WalletTransactionData();
        $transactionData->appId           = WalletSystemAppEnum::WITHDRAWAL->value;
        $transactionData->outTradeNo      = $withdrawal->withdrawal_no;
        $transactionData->direction       = AmountDirectionEnum::UNFROZEN;
        $transactionData->amount          = $withdrawal->amount;
        $transactionData->transactionType = TransactionTypeEnum::WITHDRAWAL;
        $this->walletService->transaction($wallet, $transactionData);
        $this->walletRepository->update($wallet);

        return true;
    }


    /**
     * @param  WalletWithdrawal  $withdrawal
     * @param  PaymentTransferData  $data
     *
     * @return void
     * @throws WalletWithdrawalException
     */
    public function handleTransferCallback(WalletWithdrawal $withdrawal, PaymentTransferData $data) : void
    {
        $withdrawal->paymentCallback($data);
        switch ($withdrawal->payment_status) {
            case PaymentStatusEnum::SUCCESS:
                $withdrawal->success();
                break;
            case PaymentStatusEnum::FAIL:
                $this->refundWallet($withdrawal);
                $withdrawal->fail();
                break;
            case PaymentStatusEnum::PAYING:
                break;
            case PaymentStatusEnum::PREPARE:
                throw new WalletWithdrawalException('To be implemented');
                // 无需处理
                break;

        }

    }
}