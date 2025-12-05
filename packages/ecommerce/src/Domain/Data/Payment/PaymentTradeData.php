<?php

namespace RedJasmine\Ecommerce\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;


class PaymentTradeData extends Data
{

    /**
     * 支付订单号
     * @var string
     */
    public string $merchantTradeNo;
    /**
     * 原始订单号
     * @var string
     */
    public string $merchantTradeOrderNo;

    public Money $paymentAmount;

    public ?UserInterface $buyer = null;

    public ?UserInterface $seller = null;

    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

}