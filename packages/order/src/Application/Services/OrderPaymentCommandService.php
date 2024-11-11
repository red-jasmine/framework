<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Application\Services\Handlers\Payments\OrderPaymentFailCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Payments\OrderPaymentPaidCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Payments\OrderPaymentPayingCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Payments\OrderPaymentFailCommand;
use RedJasmine\Order\Application\UserCases\Commands\Payments\OrderPaymentPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\Payments\OrderPaymentPayingCommand;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see OrderPaymentPayingCommandHandler::handle()
 * @method void paying(OrderPaymentPayingCommand $command)
 * @see OrderPaymentPaidCommandHandler::handle()
 * @method void paid(OrderPaymentPaidCommand $command)
 * @see OrderPaymentFailCommandHandler::handle()
 * @method void fail(OrderPaymentFailCommand $command)
 */
class OrderPaymentCommandService extends ApplicationCommandService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'order.application.order-payment.command';

    protected static string $modelClass = OrderPayment::class;


    protected static $macros = [
        'paying' => OrderPaymentPayingCommandHandler::class,
        'paid'   => OrderPaymentPaidCommandHandler::class,
        'fail'   => OrderPaymentFailCommandHandler::class,


    ];
}
