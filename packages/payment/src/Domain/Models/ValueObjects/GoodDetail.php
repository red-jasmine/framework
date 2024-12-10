<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

class GoodDetail extends Data
{

    public string $goodsName;

    public int $quantity;

    public string $price;

    public ?string $goodsId;

    public ?string $category;
}
