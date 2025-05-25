<?php

namespace RedJasmine\Order\Application\Services\Orders;

use RedJasmine\Order\Application\Services\Orders\Commands\OrderAcceptCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderAcceptCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderBuyerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderBuyerMessageCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderBuyerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCancelCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCardKeyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCardKeyShippingCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderDummyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderDummyShippingCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderHiddenCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderLogisticsShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderLogisticsShippingCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderMessageCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderRejectCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderRemarksCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderSellerCustomStatusCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderSellerCustomStatusCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderSellerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderSellerMessageCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderSellerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderStarCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderStarCommandHandler;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderUrgeCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderUrgeCommandHandler;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Order\Domain\Transformers\OrderTransformer;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\ApplicationService;


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
class OrderApplicationService extends ApplicationService
{

    public function __construct(
        public OrderRepositoryInterface $repository,
        public OrderReadRepositoryInterface $readRepository,
        public OrderShippingService $orderShippingService,
        public OrderTransformer $transformer,
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
