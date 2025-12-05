<?php

namespace RedJasmine\Ecommerce\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Foundation\Data\Data;


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
    public string $tradeNo;
    public Money  $amount;

    public ?string $url;

    public array $methods = [];
}