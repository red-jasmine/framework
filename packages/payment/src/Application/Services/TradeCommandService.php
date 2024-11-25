<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\TradePreCreateCommandHandler;
use RedJasmine\Payment\Domain\Models\PaymentTrade;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see  TradePreCreateCommandHandler::handle()
 * @method PaymentTrade preCreate(TradePreCreateCommand $command)
 */
class TradeCommandService extends ApplicationCommandService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.trade.command';

    protected static string $modelClass = PaymentTrade::class;


    protected static $macros = [
        'preCreate' => TradePreCreateCommandHandler::class,
    ];

}
