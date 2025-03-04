<?php

namespace RedJasmine\Payment\Domain\Data\Trades;


use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

class PaymentTradeResult extends Data
{
    public string $gateway;

    public string $merchantAppId;

    /**
     * 支付ID
     * @var string
     */
    public string $tradeNo;
    public Money $amount;

    public ?string $url;

    /**
     * @var PaymentMethod[]
     */
    public array $methods = [];

}