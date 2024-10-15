<?php

namespace RedJasmine\Shopping\Domain\Orders\Hooks;


use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Support\Foundation\Hook\Closure;
use RedJasmine\Support\Foundation\Hook\Hookable;


/**
 * 获取商品优惠
 *
 * @method static Amount hook(ProductPriceData $productPriceData, \Closure $closure)
 */
class ShoppingOrderProductDiscountHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.calculation.product.discount';


}
