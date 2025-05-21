<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class GoodDetail extends Data
{

    public string $goodsName;

    public int $quantity = 1;

    public MoneyOld $price;

    public ?string $goodsId;

    public ?string $category;
}
