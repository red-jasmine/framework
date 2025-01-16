<?php

namespace RedJasmine\Payment\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Payment\Domain\Data\TradeData;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class TradeTransformer implements TransformerInterface
{
    public function transform(
        TradeData|Data   $data,
        Trade|Model|null $trade = null
    ) : Trade
    {

        $trade                              = $trade ?? Trade::make();
        $trade->merchant_trade_no           = $data->merchantTradeNo;
        $trade->merchant_trade_order_no     = $data->merchantTradeOrderNo;
        $trade->subject                     = $data->subject;
        $trade->description                 = $data->description;
        $trade->expired_time                = $data->expiredTime;
        $trade->amount                      = $data->amount;
        $trade->is_settle_sharing           = $data->isSettleSharing;
        $trade->extension->return_url       = $data->returnUrl;
        $trade->extension->notify_url       = $data->notifyUrl;
        $trade->extension->pass_back_params = $data->passBackParams;
        $trade->setGoodsDetails($data->goodDetails);

        return $trade;
    }
}
