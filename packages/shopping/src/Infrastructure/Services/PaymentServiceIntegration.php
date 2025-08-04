<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Illuminate\Support\Facades\Config;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Shopping\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderPaymentData;
use RedJasmine\Shopping\Domain\Data\PaymentTradeResult;

class PaymentServiceIntegration implements PaymentServiceInterface
{

    public function __construct(
        public TradeApplicationService $tradeCommandService,

    ) {
    }

    protected function getMerchantAppId() : int
    {

        return (int) Config::get('red-jasmine-shopping.payment.merchant_app_id');
    }

    /**
     * 创建支付单
     * @return mixed
     */
    public function create(OrderPaymentData $orderPayment) : PaymentTradeResult
    {
        $title                                    = ''; // TODO
        $goodDetails                              = []; // TODO
        $tradeCreateCommand                       = new TradeCreateCommand;
        $tradeCreateCommand->amount               = $orderPayment->paymentAmount;
        $tradeCreateCommand->subject              = filled($title) ? $title : '支付订单：'.$orderPayment->orderNo;
        $tradeCreateCommand->goodDetails          = [];
        $tradeCreateCommand->merchantTradeNo      = $orderPayment->id;
        $tradeCreateCommand->merchantTradeOrderNo = $orderPayment->orderNo;
        $tradeCreateCommand->description          = '';
        $tradeCreateCommand->goodDetails          = $goodDetails;
        // 配置的商户应用ID
        $tradeCreateCommand->merchantAppId  = $this->getMerchantAppId();
        $tradeCreateCommand->notifyUrl      = '';
        $tradeCreateCommand->passBackParams = null;

        $paymentTrade = $this->tradeCommandService->create($tradeCreateCommand);

        $result = $this->tradeCommandService->getSdkResult($paymentTrade);

        return PaymentTradeResult::from($result);
    }


}