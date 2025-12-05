<?php

namespace RedJasmine\Ecommerce\Domain\Data\Payment;

use Cknow\Money\Money;
use RedJasmine\Support\Foundation\Data\Data;


class GoodDetailData extends Data
{
    public string $goodsName;

    public int $quantity = 1;

    public Money $price;

    public ?string $goodsId;

    public ?string $category;
}