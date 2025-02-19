<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Application\Services\Handlers\OrderAcceptCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderCancelCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderConfirmCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderCreateCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderPaidCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderPayingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderProgressCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderRejectCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerMessageCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerCustomStatusCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerMessageCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderStarCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderUrgeCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderCardKeyShippingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderLogisticsShippingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderDummyShippingCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\OrderAcceptCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderMessageCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderSellerCustomStatusCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderStarCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderUrgeCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderCardKeyShippingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderLogisticsShippingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @see  OrderCreateCommandHandler::handle()
 * @method Order create(OrderCreateCommand $command)
 * @see  OrderCancelCommandHandler::handle()
 * @method void cancel(OrderCancelCommand $command)
 * @see  OrderPayingCommandHandler::handle()
 * @method OrderPayment paying(OrderPayingCommand $command)
 * @see  OrderPaidCommandHandler::handle()
 * @method bool paid(OrderPaidCommand $command)
 * @see  OrderAcceptCommandHandler::handle()
 * @method void accept(OrderAcceptCommand $command)
 * @see  OrderRejectCommandHandler::handle()
 * @method void reject(OrderAcceptCommand $command)
 * @see  OrderLogisticsShippingCommandHandler::handle()
 * @method void logisticsShipping(OrderLogisticsShippingCommand $command)
 * @see  OrderCardKeyShippingCommandHandler::handle()
 * @method void cardKeyShipping(OrderCardKeyShippingCommand $command)
 * @see  OrderDummyShippingCommandHandler::handle()
 * @method void dummyShipping(OrderDummyShippingCommand $command)
 * @see  OrderProgressCommandHandler::handle()
 * @method int progress(OrderProgressCommand $command)
 * @see  OrderSellerRemarksCommandHandler::handle()
 * @method void sellerRemarks(OrderRemarksCommand $command)
 * @see  OrderBuyerRemarksCommandHandler::handle()
 * @method void buyerRemarks(OrderRemarksCommand $command)
 * @see  OrderBuyerMessageCommandHandler::handle()
 * @method void buyerMessage(OrderMessageCommand $command)
 * @see  OrderSellerMessageCommandHandler::handle()
 * @method void sellerMessage(OrderMessageCommand $command)
 * @see  OrderSellerHiddenCommandHandler::handle()
 * @method void sellerHidden(OrderHiddenCommand $command)
 * @see  OrderBuyerHiddenCommandHandler::handle()
 * @method void buyerHidden(OrderHiddenCommand $command)
 * @see  OrderSellerCustomStatusCommandHandler::handle()
 * @method void sellerCustomStatus(OrderSellerCustomStatusCommand $command)
 * @see  OrderConfirmCommandHandler::handle()
 * @method void confirm(OrderConfirmCommand $command)
 * @see  OrderStarCommandHandler::handle()
 * @method void star(OrderStarCommand $command)
 * @see  OrderUrgeCommandHandler::handle()
 * @method void urge(OrderUrgeCommand $command)
 */
class OrderCommandService extends ApplicationCommandService
{

    public function __construct(
        public OrderRepositoryInterface $repository,
        public OrderShippingService $orderShippingService
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'order.application.order.command';

    protected static string $modelClass = Order::class;


    protected static $macros = [
        'create'             => OrderCreateCommandHandler::class,
        'paying'             => OrderPayingCommandHandler::class,
        'paid'               => OrderPaidCommandHandler::class,
        'cancel'             => OrderCancelCommandHandler::class,
        'accept'             => OrderAcceptCommandHandler::class,
        'reject'             => OrderRejectCommandHandler::class,
        'logisticsShipping'  => OrderLogisticsShippingCommandHandler::class,
        'cardKeyShipping'    => OrderCardKeyShippingCommandHandler::class,
        'dummyShipping'      => OrderDummyShippingCommandHandler::class,
        'confirm'            => OrderConfirmCommandHandler::class,
        'progress'           => OrderProgressCommandHandler::class,
        'sellerRemarks'      => OrderSellerRemarksCommandHandler::class,
        'buyerRemarks'       => OrderBuyerRemarksCommandHandler::class,
        'buyerMessage'       => OrderBuyerMessageCommandHandler::class,
        'sellerMessage'      => OrderSellerMessageCommandHandler::class,
        'sellerCustomStatus' => OrderSellerCustomStatusCommandHandler::class,
        'sellerHidden'       => OrderSellerHiddenCommandHandler::class,
        'buyerHidden'        => OrderBuyerHiddenCommandHandler::class,
        'star'               => OrderStarCommandHandler::class,
        'urge'               => OrderUrgeCommandHandler::class,
    ];


}
