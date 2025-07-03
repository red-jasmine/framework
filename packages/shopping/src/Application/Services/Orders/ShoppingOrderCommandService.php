<?php

namespace RedJasmine\Shopping\Application\Services\Orders;

use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Shopping\Application\Services\Orders\Commands\OrderPayCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\OrderPayCommandHandler;
use RedJasmine\Shopping\Application\Services\Orders\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\ProductBuyCommandHandler;
use RedJasmine\Shopping\Application\Services\Orders\Commands\ProductCalculateCommandHandler;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see ProductBuyCommandHandler::handle()
 * @method Collection<Order> buy(ProductBuyCommand $command)
 * @see OrderPayCommandHandler::handle()
 * @method pay(OrderPayCommand $command)
 */
class ShoppingOrderCommandService extends ApplicationService
{

    protected static $macros = [
        'calculate' => ProductCalculateCommandHandler::class,
        'buy'       => ProductBuyCommandHandler::class,
        'pay'       => OrderPayCommandHandler::class,
    ];

}
