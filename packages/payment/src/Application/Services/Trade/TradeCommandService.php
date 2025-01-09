<?php

namespace RedJasmine\Payment\Application\Services\Trade;

use RedJasmine\Payment\Application\Services\Trade\Commands\TradePaidCommandHandler;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommandHandler;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePreCreateCommandHandler;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommandHandler;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Domain\Services\Routing\TradeRoutingService;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see  \RedJasmine\Payment\Application\Services\Trade\Commands\TradePreCreateCommandHandler::handle()
 * @method Trade preCreate(Commands\TradePreCreateCommand $command)
 * @see  \RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommandHandler::handle()
 * @method Trade ready(TradeReadyCommand $command)
 * @see  \RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommandHandler::handle()
 * @method ChannelTradeData paying(Commands\TradePayingCommand $command)
 * @see  TradePaidCommandHandler::handle()
 * @method bool paid(Commands\TradePaidCommand $command)
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
