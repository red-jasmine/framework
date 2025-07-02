<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;

class CalculateAmountCommand extends PurchaseFactor
{

    /**
     * 选择的购物车中商品
     * @var array
     */
    public array $cartProducts;

} 