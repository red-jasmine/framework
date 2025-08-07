<?php

namespace RedJasmine\Shopping\Application\Services\Orders;

use Illuminate\Support\Collection;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Shopping\Application\Services\Orders\Commands\PaidCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\PaidCommandHandler;
use RedJasmine\Shopping\Application\Services\Orders\Commands\PayCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\PayCommandHandler;
use RedJasmine\Shopping\Application\Services\Orders\Commands\BuyCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\BuyCommandHandler;
use RedJasmine\Shopping\Application\Services\Orders\Commands\CheckCommand;
use RedJasmine\Shopping\Application\Services\Orders\Commands\CheckCommandHandler;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see BuyCommandHandler::handle()
 * @method Collection<Order> buy(BuyCommand $command)
 * @see PayCommandHandler::handle()
 * @method pay(PayCommand $command)
 * @see CheckCommandHandler::handle()
 * @method check(CheckCommand $command)
 * @see PaidCommandHandler::handle()
 * @method paid(PaidCommand $command)
 */
class ShoppingOrderCommandService extends ApplicationService
{

    protected static $macros = [
        'check' => CheckCommandHandler::class,
        'buy'   => BuyCommandHandler::class,
        'pay'   => PayCommandHandler::class,
        'paid'  => PaidCommandHandler::class,
    ];

}
