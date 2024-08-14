<?php

namespace RedJasmine\Shopping\Application\Services;

use RedJasmine\Shopping\Application\Services\CommandHandlers\OrderBuyCommandHandler;
use RedJasmine\Support\Application\ApplicationCommandService;

class OrderCommandService extends ApplicationCommandService
{

    //
    // check 确认
    // buy 下单


    protected static $macros = [
        'buy' => OrderBuyCommandHandler::class,
    ];


}
