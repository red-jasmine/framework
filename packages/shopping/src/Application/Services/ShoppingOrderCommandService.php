<?php

namespace RedJasmine\Shopping\Application\Services;

use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Shopping\Application\Services\Commands\OrderPayCommandHandler;
use RedJasmine\Shopping\Application\Services\Commands\ProductBuyCommandHandler;
use RedJasmine\Shopping\Application\Services\Commands\ProductCalculateCommandHandler;
use RedJasmine\Shopping\Application\Services\Commands\ProductBuyCommand;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see ProductBuyCommandHandler::handle()
 * @method Collection<Order> buy(ProductBuyCommand $command)
 */
class ShoppingOrderCommandService extends ApplicationCommandService
{

    protected static $macros = [
        'calculate' => ProductCalculateCommandHandler::class,
        'buy'       => ProductBuyCommandHandler::class,
        'pay'       => OrderPayCommandHandler::class,
    ];

}
