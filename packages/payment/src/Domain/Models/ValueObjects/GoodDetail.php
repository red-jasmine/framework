<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;


class GoodDetail extends Data
{

    public string $goodsName;

    public int $quantity = 1;

    public Money $price;

    public ?string $goodsId;

    public ?string $category;
}
