<?php

namespace RedJasmine\Shopping\Application\Services;

use RedJasmine\Shopping\Application\Services\CommandHandlers\OrderBuyCommandHandler;
use RedJasmine\Shopping\Application\Services\CommandHandlers\OrderCalculateCommandHandler;
use RedJasmine\Support\Application\ApplicationCommandService;

class OrderCommandService extends ApplicationCommandService
{

    protected static $macros = [
        'buy'       => OrderBuyCommandHandler::class,
        'calculate' => OrderCalculateCommandHandler::class,
    ];


}
