<?php

namespace RedJasmine\Shopping\Domain\Hooks;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Shopping\Domain\Orders\Hooks\Order;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * @method static Order hook(OrderCreateCommand $command, \Closure $closure)
 */
class ShoppingOrderToOrderDomainCreateHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.create.order.domain.create';
}
