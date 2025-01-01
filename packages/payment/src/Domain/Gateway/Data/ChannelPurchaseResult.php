<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Payment\Domain\Data\PaymentTrigger;

class ChannelPurchaseResult extends AbstractChannelResult
{

    public ?PaymentTrigger $paymentTrigger = null;


    public ?string $tradeNo = null;


    public function getTradeNo() : ?string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(?string $tradeNo) : static
    {
        $this->tradeNo = $tradeNo;
        return $this;
    }


}
