<?php

namespace RedJasmine\Ecommerce\Domain\Data\Product;

use RedJasmine\Support\Foundation\Data\Data;

class StockInfo extends Data
{

    /**
     * 是否可用
     * @var bool
     */
    public bool $isAvailable;
    /**
     * 库存数量
     * @var int
     */
    public int $stock;


}