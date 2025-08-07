<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Illuminate\Support\Facades\Config;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Shopping\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;


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
    public function create(PaymentTradeData $orderPayment) : PaymentTradeResult
    {

        $tradeCreateCommand                       = new TradeCreateCommand;
        $tradeCreateCommand->biz                  = static::BIZ;
        $tradeCreateCommand->amount               = $orderPayment->paymentAmount;
        $tradeCreateCommand->subject              = '支付订单：'.$orderPayment->merchantTradeOrderNo;
        $tradeCreateCommand->merchantTradeNo      = $orderPayment->merchantTradeNo;
        $tradeCreateCommand->merchantTradeOrderNo = $orderPayment->merchantTradeOrderNo;
        $tradeCreateCommand->description          = '';
        $tradeCreateCommand->goodDetails          = GoodDetailData::collect($orderPayment->goodDetails);
        // 配置的商户应用ID
        $tradeCreateCommand->merchantAppId  = $this->getMerchantAppId();
        $tradeCreateCommand->notifyUrl      = '';
        $tradeCreateCommand->passBackParams = null;


        $paymentTrade = $this->tradeCommandService->create($tradeCreateCommand);

        $result = $this->tradeCommandService->getSdkResult($paymentTrade);

        return PaymentTradeResult::from($result);
    }


}