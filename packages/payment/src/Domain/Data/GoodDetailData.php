<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

class GoodDetailData extends Data
{


    public string  $goodsName;
    public string  $price;
    public int     $quantity;
    public ?string $goodsId;

    public ?string $category;

}
