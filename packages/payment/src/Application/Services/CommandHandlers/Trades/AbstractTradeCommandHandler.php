<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Trades;

use RedJasmine\Payment\Application\Services\TradeCommandService;
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
