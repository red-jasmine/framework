<?php

namespace RedJasmine\Wallet\Domain\Services;

use Cknow\Money\Money;
use Exception;
use Money\Currency;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Wallet\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Wallet\Domain\Data\Config\ExchangeCurrencyConfigData;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletTradeData;
use RedJasmine\Wallet\Domain\Data\Recharge\RechargePaymentData;
use RedJasmine\Wallet\Domain\Data\WalletRechargeData;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Exceptions\WalletException;
use RedJasmine\Wallet\Exceptions\WalletRechargeException;

class WalletRechargeService extends Service
{
    public function __construct(
        protected WalletService $walletService,
        protected PaymentServiceInterface $paymentService,
    ) {
    }

    /**
     * @param  Wallet  $wallet
     * @param  WalletRechargeData  $data
     *
     * @return WalletRecharge
     * @throws WalletException
     */
    public function create(Wallet $wallet, WalletRechargeData $data) : WalletRecharge
    {
        // 验证是否允许充值

        $amount = $data->amount->absolute();

        $walletConfig = $this->walletService->getWalletConfig($wallet->type);

        // 判断是否允许充值
        if (!$walletConfig->recharge || !$walletConfig->recharge->state) {
            throw new WalletException('钱包不允许充值');
        }
        // 获取钱包配置
        $currencies = collect($walletConfig->recharge->currencies)->keyBy('currency')->all();
        /**
         * @var ExchangeCurrencyConfigData $currencyConfig
         */
        $currencyConfig = $currencies[$data->paymentCurrency];

        // 计算支付金额

        $paymentAmount = Money::parse($amount->multiply($currencyConfig->exchangeRate)->getAmount(),
            new Currency($currencyConfig->currency)
        );
        // 计算手续费
        $paymentFee = $paymentAmount->multiply($currencyConfig->feeRate)->absolute();
        // 计算总支付金额
        $totalPaymentAmount = $paymentAmount->add($paymentFee);


        // 根据充值金额 获取充值配置
        $walletRecharge = WalletRecharge::make([]);

        $walletRecharge->wallet_id            = $wallet->id;
        $walletRecharge->wallet_type          = $wallet->type;
        $walletRecharge->owner                = $wallet->owner;
        $walletRecharge->amount               = $amount;
        $walletRecharge->payment_amount       = $paymentAmount;
        $walletRecharge->payment_fee          = $paymentFee;
        $walletRecharge->total_payment_amount = $totalPaymentAmount;
        $walletRecharge->exchange_rate        = $currencyConfig->exchangeRate;
        $walletRecharge->status               = RechargeStatusEnum::CREATED;
        $walletRecharge->payment_status       = PaymentStatusEnum::PREPARE;

        return $walletRecharge;
    }


    /**
     * @param  WalletRecharge  $walletRecharge
     *
     * @return PaymentTradeData
     */
    public function createPayment(WalletRecharge $walletRecharge) : PaymentTradeData
    {

        $walletPayment = new WalletTradeData();

        $walletPayment->businessNo = $walletRecharge->no;
        $walletPayment->amount     = $walletRecharge->total_payment_amount;

        $paymentTradeData = $this->paymentService->createTrade($walletPayment);
        // 创建收款单
        $walletRecharge->payment_type   = $paymentTradeData->paymentType;
        $walletRecharge->payment_id     = $paymentTradeData->paymentId;
        $walletRecharge->payment_status = PaymentStatusEnum::PAYING;

        return $paymentTradeData;

    }


}