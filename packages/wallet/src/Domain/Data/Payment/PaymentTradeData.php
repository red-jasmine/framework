<?php

namespace RedJasmine\Wallet\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

class PaymentTradeData extends Data
{
    /**
     * 业务单号
     * @var string
     */
    public string $businessNo;

    public Money $amount;

    public string $paymentType;

    public string $paymentId;

    public array $context = [];

}