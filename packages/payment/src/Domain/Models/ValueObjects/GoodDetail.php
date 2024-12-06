<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

class GoodDetail extends Data
{

    public string  $goodsName;
    public string  $price;
    public int     $quantity;
    public ?string $goodsId;

    public ?string $category;
}
