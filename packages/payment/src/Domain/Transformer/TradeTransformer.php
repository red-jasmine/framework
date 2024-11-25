<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Domain\Data\TradeData;
use RedJasmine\Payment\Domain\Models\PaymentTrade;

class TradeTransformer
{
    public function transform(TradeData $data, ?PaymentTrade $trade = null) : PaymentTrade
    {
        $trade                    = $trade ?? PaymentTrade::newModel();
        $trade->merchant_app_id   = $data->merchantAppId;
        $trade->merchant_order_no = $data->merchantOrderNo;
        $trade->currency          = $data->currency;
        $trade->amount            = $data->amount;
        $trade->subject           = $data->subject;
        $trade->description       = $data->description;
        $trade->expired_time      = $data->expiredTime;

        $trade->extension->detail = $data->goodDetails;


        return $trade;
    }
}
