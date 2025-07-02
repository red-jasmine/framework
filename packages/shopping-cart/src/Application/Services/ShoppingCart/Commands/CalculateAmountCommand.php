<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\Ecommerce\Domain\Data\PurchaseFactors;

class CalculateAmountCommand extends PurchaseFactors
{

    /**
     * 选择的购物车中商品
     * @var array
     */
    public array $cartProducts;

} 