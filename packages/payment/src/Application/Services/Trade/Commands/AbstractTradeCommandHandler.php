<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Support\Application\CommandHandler;

/**
 * @property TradeCommandService $service
 */
abstract class AbstractTradeCommandHandler extends CommandHandler
{

    public function __construct(protected TradeCommandService $service)
    {
    }

}
