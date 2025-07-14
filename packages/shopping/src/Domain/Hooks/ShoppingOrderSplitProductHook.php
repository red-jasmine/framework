<?php

namespace RedJasmine\Shopping\Domain\Hooks;

use Closure;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * 拆分商品 返回拆分key
 *
 * @method static string hook(ProductPurchaseFactor $productData, Closure $closure)
 */
class ShoppingOrderSplitProductHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.split';
}
