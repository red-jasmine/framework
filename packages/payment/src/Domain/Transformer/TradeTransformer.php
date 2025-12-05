<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Domain\Data\TradeData;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class TradeTransformer implements TransformerInterface
{
    /**
     * @param  TradeData  $data
     * @param  Trade  $model
     *
     * @return Trade
     */
    public function transform($data, $model) : Trade
    {
        $model->biz           = $data->biz;
        $model->merchant_trade_no           = $data->merchantTradeNo;
        $model->merchant_trade_order_no     = $data->merchantTradeOrderNo;
        $model->subject                     = $data->subject;
        $model->description                 = $data->description;
        $model->expired_time                = $data->expiredTime;
        $model->amount                      = $data->amount;
        $model->is_settle_sharing           = $data->isSettleSharing;
        $model->extension->return_url       = $data->returnUrl;
        $model->extension->notify_url       = $data->notifyUrl;
        $model->extension->pass_back_params = $data->passBackParams;
        $model->setGoodsDetails($data->goodDetails);


        return $model;
    }
}
