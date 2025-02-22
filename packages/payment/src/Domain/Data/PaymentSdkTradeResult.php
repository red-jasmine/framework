<?php

namespace RedJasmine\Payment\Domain\Data;


use RedJasmine\Support\Data\Data;

class PaymentSdkTradeResult extends Data
{


    /**
     * 支付ID
     * @var string
     */
    public string $tradeNo;


    public ?string $url;

}