<?php

namespace RedJasmine\ShoppingCart\Domain\Data;

use RedJasmine\Support\Data\Data;

class CartStockInfo extends Data
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