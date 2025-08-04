<?php

namespace RedJasmine\Shopping\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class OrderPaymentData extends Data
{
    public int $id;

    public string $orderNo;

    public Money $paymentAmount;

    public UserInterface $buyer;

    public UserInterface $seller;

    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

}