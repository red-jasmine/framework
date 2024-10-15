<?php

namespace RedJasmine\Shopping\Domain\Orders\Hooks;

use RedJasmine\Shopping\Domain\Data\ProductData;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * 拆分商品 返回拆分key
 *
 * @method static string hook(ProductData $productData, \Closure $closure)
 */
class ShoppingOrderSplitProductHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.split';
}
