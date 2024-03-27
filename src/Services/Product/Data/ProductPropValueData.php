<?php

namespace RedJasmine\Product\Services\Product\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Optional;

class ProductPropValueData extends Data
{

    public string|Optional $name;
    /**
     * 属性值
     * @var int|array|string
     */
    public int|array|string $vid;
}
