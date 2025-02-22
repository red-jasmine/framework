<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Data\PaymentSdkTradeResult;
use RedJasmine\Payment\Domain\Models\Trade;

class PaymentSdkService
{


    public function __construct(
        protected  PaymentUrlService $paymentUrlService,
    )
    {
    }

    // 生成调用SDK 的 参数


    public function init(Trade $trade) : PaymentSdkTradeResult
    {

        $paymentSdkTradeResult = new  PaymentSdkTradeResult;

        $paymentSdkTradeResult->tradeNo = $trade->trade_no;
        // 支付网关
        // 订单号
        // 调用字符串
        // 支付链接
        $paymentSdkTradeResult->url = $this->paymentUrlService->returnUrl($trade);


        return $paymentSdkTradeResult;

    }

}