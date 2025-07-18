<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;

/**
 * @property TradeApplicationService $service
 */
abstract class AbstractTradeCommandHandler extends CommandHandler
{

    public function __construct(protected TradeApplicationService $service)
    {
    }

}
