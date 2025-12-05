<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;


class GoodDetail extends Data
{

    public string $goodsName;

    public int $quantity = 1;

    public Money $price;

    public ?string $goodsId;

    public ?string $category;
}
