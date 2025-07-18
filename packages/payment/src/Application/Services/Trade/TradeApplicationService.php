<?php

namespace RedJasmine\Payment\Application\Services\Trade;

use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommandHandler;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePaidCommandHandler;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradePayingCommandHandler;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommand;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeReadyCommandHandler;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Data\Trades\PaymentTradeResult;
use RedJasmine\Payment\Domain\Facades\PaymentSDK;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Domain\Services\Routing\TradeRoutingService;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see  TradeCreateCommandHandler::handle
 * @method Trade create(Commands\TradeCreateCommand $command)
 * @see  TradeReadyCommandHandler::handle
 * @method PaymentTradeResult ready(TradeReadyCommand $command)
 * @see  TradePayingCommandHandler::handle
 * @method ChannelTradeData paying(Commands\TradePayingCommand $command)
 * @see  TradePaidCommandHandler::handle()
 * @method bool paid(Commands\TradePaidCommand $command)
 */
class TradeApplicationService extends ApplicationService
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
        'create' => TradeCreateCommandHandler::class,
        'ready'  => TradeReadyCommandHandler::class,
        'paying' => TradePayingCommandHandler::class,
        'paid'   => TradePaidCommandHandler::class,
    ];


    public function getSdkResult(Trade $trade) : PaymentTradeResult
    {
        return PaymentSDK::init($trade);
    }
}
