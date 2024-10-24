<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Application\Services\Handlers\OrderCancelCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderConfirmCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderCreateCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderPaidCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderPayingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderProgressCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerMessageCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerCustomStatusCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerMessageCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderCardKeyShippingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderLogisticsShippingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderDummyShippingCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderMessageCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderCardKeyShippingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderLogisticsShippingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method Order create(OrderCreateCommand $command)
 * @method void cancel(OrderCancelCommand $command)
 * @method OrderPayment paying(OrderPayingCommand $command)
 * @method bool paid(OrderPaidCommand $command)
 * @method void logisticsShipping(OrderLogisticsShippingCommand $command)
 * @method void cardKeyShipping(OrderCardKeyShippingCommand $command)
 * @method void dummyShipping(OrderDummyShippingCommand $command)
 * @method int progress(OrderProgressCommand $command)
 * @method void sellerRemarks(OrderRemarksCommand $command)
 * @method void buyerRemarks(OrderRemarksCommand $command)
 * @method void buyerMessage(OrderMessageCommand $command)
 * @method void sellerMessage(OrderMessageCommand $command)
 * @method void sellerHidden(OrderHiddenCommand $command)
 * @method void buyerHidden(OrderHiddenCommand $command)
 * @see OrderConfirmCommandHandler::handle()
 * @method void confirm(OrderConfirmCommand $command)
 */
class OrderCommandService extends ApplicationCommandService
{

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
    ];
    // TODO
    // 1、补发


}
