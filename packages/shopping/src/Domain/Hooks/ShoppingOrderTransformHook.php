<?php

namespace RedJasmine\Shopping\Domain\Hooks;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * @method static OrderCreateCommand hook(OrderData $orderData, \Closure $closure)
 */
class ShoppingOrderTransformHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.create.transform';
}
