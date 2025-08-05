<?php

namespace RedJasmine\Ecommerce\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;


class PaymentTradeData extends Data
{

    public string $merchantTradeNo;
    public string $merchantTradeOrderNo;

    public Money $paymentAmount;

    public UserInterface $buyer;

    public UserInterface $seller;

    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

}