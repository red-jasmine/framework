<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Application\Commands\Trade\TradeData;
use RedJasmine\Payment\Domain\Models\Trade;

class TradeTransformer
{
    public function transform(TradeData $data, ?Trade $trade = null) : Trade
    {
        $trade                    = $trade ?? Trade::newModel();
        $trade->merchant_order_no = $data->merchantOrderNo;
        $trade->subject           = $data->subject;
        $trade->description       = $data->description;
        $trade->expired_time      = $data->expiredTime;
        $trade->amount            = $data->amount;
        $trade->setGoodsDetails($data->goodDetails);
        return $trade;
    }
}
