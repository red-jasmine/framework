<?php

namespace RedJasmine\Shopping\Domain\Orders\Hooks;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * @method static Order hook(OrderData $orderData, \Closure $closure)
 */
class ShoppingOrderCreateHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.create';
}
