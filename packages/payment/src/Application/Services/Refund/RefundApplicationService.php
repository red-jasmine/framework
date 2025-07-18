<?php

namespace RedJasmine\Payment\Application\Services\Refund;

use RedJasmine\Payment\Application\Services\Refund\Commands\RefundCancelCommandHandler;
use RedJasmine\Payment\Application\Services\Refund\Commands\RefundCreateCommand;
use RedJasmine\Payment\Application\Services\Refund\Commands\RefundCreateCommandHandler;
use RedJasmine\Payment\Application\Services\Refund\Commands\RefundExecutingCommand;
use RedJasmine\Payment\Application\Services\Refund\Commands\RefundExecutingCommandHandler;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see  RefundCreateCommandHandler::handle()
 * @method Refund create(RefundCreateCommand $command)
 * @see  RefundExecutingCommandHandler::handle()
 * @method bool executing(RefundExecutingCommand $command)
 * @see  RefundCancelCommandHandler::handle()
 * * @method bool cancel(RefundExecutingCommand $command)
 */
class RefundApplicationService extends ApplicationService
{

    public function __construct(
        public TradeRepositoryInterface $tradeRepository,
        public RefundRepositoryInterface $refundRepository,

    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.refund.command';

    protected static string $modelClass = Refund::class;

    protected static $macros = [
        'create'    => RefundCreateCommandHandler::class,
        'executing' => RefundExecutingCommandHandler::class,
        'cancel'    => RefundCancelCommandHandler::class,
    ];
}
