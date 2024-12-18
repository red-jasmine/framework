<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Trade\TradePayingCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradePayingCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradePreCreateCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradeReadyCommandHandler;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see  TradePreCreateCommandHandler::handle()
 * @method Trade preCreate(TradePreCreateCommand $command)
 * @see  TradeReadyCommandHandler::handle()
 * @method Trade ready(TradeReadyCommand $command)
 * @see  TradePayingCommandHandler::handle()
 * @method ChannelTradeData paying(TradePayingCommand $command)
 */
class TradeCommandService extends ApplicationCommandService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.trade.command';

    protected static string $modelClass = Trade::class;


    protected static $macros = [
        'preCreate' => TradePreCreateCommandHandler::class,
        'ready'     => TradeReadyCommandHandler::class,
        'paying'    => TradePayingCommandHandler::class,
    ];

}
