<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Application\Commands\Trade\TradeData;
use RedJasmine\Payment\Domain\Models\Trade;

class TradeTransformer
{
    public function transform(TradeData $data, ?Trade $trade = null) : Trade
    {
        $trade                    = $trade ?? Trade::newModel();
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
