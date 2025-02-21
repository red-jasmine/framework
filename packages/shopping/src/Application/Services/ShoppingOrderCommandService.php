<?php

namespace RedJasmine\Shopping\Application\Services;

use RedJasmine\Shopping\Application\Services\Commands\OrderBuyCommandHandler;
use RedJasmine\Shopping\Application\Services\Commands\OrderCalculateCommandHandler;
use RedJasmine\Shopping\Application\Services\Commands\ProductBuyCommand;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @method buy(ProductBuyCommand $command)
 */
class ShoppingOrderCommandService extends ApplicationCommandService
{

    protected static $macros = [
        'calculate' => OrderCalculateCommandHandler::class,
        'buy'       => OrderBuyCommandHandler::class,
    ];


}
