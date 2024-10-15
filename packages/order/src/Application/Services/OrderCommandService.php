<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Application\Services\Handlers\OrderCancelCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderConfirmCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderCreateCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderPaidCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderPayingCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\OrderProgressCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderBuyerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerCustomStatusCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerHiddenCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Others\OrderSellerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderShippingCardKeyCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderShippingLogisticsCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Shipping\OrderShippingVirtualCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\OrderCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingCardKeyCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingLogisticsCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderShippingVirtualCommand;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method Order create(OrderCreateCommand $command)
 * @method void cancel(OrderCancelCommand $command)
 * @method OrderPayment paying(OrderPayingCommand $command)
 * @method bool paid(OrderPaidCommand $command)
 * @method void shippingLogistics(OrderShippingLogisticsCommand $command)
 * @method void shippingCardKey(OrderShippingCardKeyCommand $command)
 * @method void shippingVirtual(OrderShippingVirtualCommand $command)
 * @method int progress(OrderProgressCommand $command)
 * @method void sellerRemarks(OrderRemarksCommand $command)
 * @method void buyerRemarks(OrderRemarksCommand $command)
 * @method void sellerHidden(OrderHiddenCommand $command)
 * @method void buyerHidden(OrderHiddenCommand $command)
 * @see OrderConfirmCommandHandler::handle()
 * @method void confirm(OrderConfirmCommand $command)
 */
class OrderCommandService extends ApplicationCommandService
{

    protected static $macros = [
        'create'             => OrderCreateCommandHandler::class,
        'paying'             => OrderPayingCommandHandler::class,
        'paid'               => OrderPaidCommandHandler::class,
        'cancel'             => OrderCancelCommandHandler::class,
        'shippingLogistics'  => OrderShippingLogisticsCommandHandler::class,
        'shippingCardKey'    => OrderShippingCardKeyCommandHandler::class,
        'shippingVirtual'    => OrderShippingVirtualCommandHandler::class,
        'confirm'            => OrderConfirmCommandHandler::class,
        'progress'           => OrderProgressCommandHandler::class,
        'sellerRemarks'      => OrderSellerRemarksCommandHandler::class,
        'buyerRemarks'       => OrderBuyerRemarksCommandHandler::class,
        'sellerCustomStatus' => OrderSellerCustomStatusCommandHandler::class,
        'sellerHidden'       => OrderSellerHiddenCommandHandler::class,
        'buyerHidden'        => OrderBuyerHiddenCommandHandler::class,
    ];
    // TODO
    // 1、补发


}
