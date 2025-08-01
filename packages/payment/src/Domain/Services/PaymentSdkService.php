<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Data\Trades\PaymentTradeResult;
use RedJasmine\Payment\Domain\Models\Trade;

class PaymentSdkService
{


    public function __construct(
        protected PaymentUrlService $paymentUrlService,
    ) {
    }

    // 生成调用SDK 的 参数


    public function init(Trade $trade) : PaymentTradeResult
    {

        $paymentSdkTradeResult = new  PaymentTradeResult;

        $paymentSdkTradeResult->merchantAppId = (string) $trade->merchant_app_id;
        $paymentSdkTradeResult->subject       = $trade->subject;
        $paymentSdkTradeResult->description   = $trade->description;
        $paymentSdkTradeResult->tradeNo       = $trade->trade_no;
        $paymentSdkTradeResult->amount        = $trade->amount;
        // 支付网关
        // 订单号
        // 调用字符串
        // 支付链接
        $paymentSdkTradeResult->url = $this->paymentUrlService->returnUrl($trade);


        return $paymentSdkTradeResult;

    }

}