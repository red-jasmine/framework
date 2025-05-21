<?php

namespace RedJasmine\Payment\Domain\Data\Trades;


use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class PaymentTradeResult extends Data
{
    public string $gateway;

    public string $merchantAppId;

    public ?string $subject;

    public ?string $description;

    /**
     * 支付ID
     * @var string
     */
    public string   $tradeNo;
    public MoneyOld $amount;

    public ?string $url;

    /**
     * @var PaymentMethod[]
     */
    public array $methods = [];

}