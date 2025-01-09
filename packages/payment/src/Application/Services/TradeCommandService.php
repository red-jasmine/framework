<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Trade\TradePaidCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradePayingCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Application\Commands\Trade\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradePaidCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradePayingCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradePreCreateCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\Trades\TradeReadyCommandHandler;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Domain\Services\Routing\TradeRoutingService;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see  TradePreCreateCommandHandler::handle()
 * @method Trade preCreate(TradePreCreateCommand $command)
 * @see  TradeReadyCommandHandler::handle()
 * @method Trade ready(TradeReadyCommand $command)
 * @see  TradePayingCommandHandler::handle()
 * @method ChannelTradeData paying(TradePayingCommand $command)
 * @see  TradePaidCommandHandler::handle()
 * @method bool paid(TradePaidCommand $command)
 */
class TradeCommandService extends ApplicationCommandService
{

    public function __construct(
        public TradeRepositoryInterface $repository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public TradeRoutingService $tradeRoutingService,
        public PaymentChannelService $paymentChannelService,
    ) {
    }

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
        'paid'      => TradePaidCommandHandler::class,
    ];

}
