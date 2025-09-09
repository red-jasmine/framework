<?php

namespace RedJasmine\Order\Application\Services\Refunds;

use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeRefundCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReshipmentCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReshipmentCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReturnGoodsCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundBuyerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCancelCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCancelCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCardKeyReshipmentCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundConfirmCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundConfirmCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundLogisticsReshipmentCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundLogisticsReshipmentCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRejectCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRejectCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundRemarksCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundReturnGoodsCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundSellerRemarksCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundStarCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundStarCommandHandler;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundUrgeCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundUrgeCommandHandler;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method int create(RefundCreateCommand $command)
 * @method void reject(RefundRejectCommand $command)
 * @method void cancel(RefundCancelCommand $command)
 * @see RefundAgreeRefundCommandHandler::handle()
 * @method void agreeRefund(RefundAgreeRefundCommand $command)
 * @method void agreeReturnGoods(RefundAgreeReturnGoodsCommand $command)
 * @method void agreeReshipment(RefundAgreeReshipmentCommand $command)
 * @method void returnGoods(RefundReturnGoodsCommand $command)
 * @method void confirm(RefundConfirmCommand $command)
 * @method void logisticsReshipment(RefundLogisticsReshipmentCommand $command)
 * @method void cardKeyReshipment(RefundCardKeyReshipmentCommand $command)
 * @method void sellerRemarks(RefundRemarksCommand $command)
 * @method void buyerRemarks(RefundRemarksCommand $command)
 * @method void star(RefundStarCommand $command)
 * @method void urge(RefundUrgeCommand $command)
 */
class RefundApplicationService extends ApplicationService
{

    public function __construct(
        public RefundRepositoryInterface $repository,
        public OrderRepositoryInterface $orderRepository,
    ) {
    }

    protected static $macros = [
        'create'              => RefundCreateCommandHandler::class,
        'reject'              => RefundRejectCommandHandler::class,
        'cancel'              => RefundCancelCommandHandler::class,
        'agreeRefund'         => RefundAgreeRefundCommandHandler::class,
        'agreeReturnGoods'    => RefundAgreeReturnGoodsCommandHandler::class,
        'agreeReshipment'     => RefundAgreeReshipmentCommandHandler::class,
        'returnGoods'         => RefundReturnGoodsCommandHandler::class,
        'confirm'             => RefundConfirmCommandHandler::class,
        'logisticsReshipment' => RefundLogisticsReshipmentCommandHandler::class,
        'cardKeyReshipment'   => RefundCardKeyReshipmentCommandHandler::class,
        'sellerRemarks'       => RefundSellerRemarksCommandHandler::class,
        'buyerRemarks'        => RefundBuyerRemarksCommandHandler::class,
        'star'                => RefundStarCommandHandler::class,
        'urge'                => RefundUrgeCommandHandler::class,
    ];

}
