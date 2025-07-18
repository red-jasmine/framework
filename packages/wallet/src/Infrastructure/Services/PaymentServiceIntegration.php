<?php

namespace RedJasmine\Wallet\Infrastructure\Services;

use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Wallet\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletPaymentData;

class PaymentServiceIntegration implements PaymentServiceInterface
{

    public function __construct(
        protected TradeApplicationService $tradeApplicationService
    ) {
    }

    /**
     * 创建付款单
     *
     * @param  WalletPaymentData  $paymentData
     *
     * @return PaymentTradeData
     */
    public function createTrade(WalletPaymentData $paymentData) : PaymentTradeData
    {
        $command                       = new TradeCreateCommand();
        $command->merchantAppId        = 1;
        $command->merchantTradeNo      = $paymentData->businessNo;
        $command->merchantTradeOrderNo = $paymentData->businessNo;
        $command->amount               = $paymentData->amount;
        $command->subject              = '充值';

        $trade = $this->tradeApplicationService->create($command);

        $tradeData              = new PaymentTradeData();
        $tradeData->businessNo  = $paymentData->businessNo;
        $tradeData->amount      = $paymentData->amount;
        $tradeData->paymentType = 'payment';
        $tradeData->paymentId   = $trade->trade_no;
        $tradeData->context     = $this->tradeApplicationService->getSdkResult($trade)->toArray();
        return $tradeData;
    }

    public function createTransfer(WalletPaymentData $paymentData) : string
    {
        // TODO: Implement createTransfer() method.
    }


    public function paying()
    {
        // TODO: Implement paying() method.
    }

    public function queryPayment()
    {
        // TODO: Implement queryPayment() method.
    }

    public function getBankCard(UserInterface $user, string $bankCardId)
    {
        // TODO: Implement getBankCard() method.
    }

    public function getBankCards(UserInterface $user)
    {
        // TODO: Implement getBankCards() method.
    }


}