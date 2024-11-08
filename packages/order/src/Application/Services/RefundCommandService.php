<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Application\Services\Handlers\Refund\RefundAgreeRefundCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundAgreeReshipmentCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundAgreeReturnGoodsCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundCancelCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundCardKeyReshipmentCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundConfirmCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundCreateCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundRejectCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundLogisticsReshipmentCommandHandler;
use RedJasmine\Order\Application\Services\Handlers\Refund\RefundReturnGoodsCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeReturnGoodsCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCancelCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundRejectCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundLogisticsReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundReturnGoodsCommand;
use RedJasmine\Support\Application\ApplicationCommandService;

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
 */
class RefundCommandService extends ApplicationCommandService
{
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
    ];

}
