<?php

namespace RedJasmine\Shopping\Domain\Hooks;

use Closure;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * @method static string hook(OrderData $orderData, Closure $closure)
 */
class ShoppingOrderCreateHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.create';
}
