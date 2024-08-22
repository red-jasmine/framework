<?php

namespace RedJasmine\Shopping\Domain\Orders\Hooks;

use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Support\Foundation\Hook\Hookable;

/**
 * @method static Order hook(OrderCreateCommand $command, \Closure $closure)
 */
class ShoppingOrderToOrderDomainCreateHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.create.order.domain.create';
}
