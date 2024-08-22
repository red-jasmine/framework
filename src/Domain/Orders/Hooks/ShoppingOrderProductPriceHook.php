<?php

namespace RedJasmine\Shopping\Domain\Orders\Hooks;


use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Support\Foundation\Hook\Closure;
use RedJasmine\Support\Foundation\Hook\Hookable;


/**
 * 购物订单获取商品基础价格组件
 *
 * @method static Amount hook(ProductPriceData $productPriceData, \Closure $closure)
 */
class ShoppingOrderProductPriceHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.calculation.product.price';


}
