<?php

namespace RedJasmine\Shopping\Domain\Orders\Hooks;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * @method static OrderCreateCommand hook(OrderData $orderData, \Closure $closure)
 */
class ShoppingOrderTransformHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.create.transform';
}
