<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Commands\PaymentChannel\ChannelRefundCreateCommand;
use RedJasmine\Payment\Application\Commands\PaymentChannel\ChannelRefundQueryCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\PaymentChannel\ChannelRefundCreateCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\PaymentChannel\ChannelRefundQueryCommandHandler;
use RedJasmine\Payment\Application\Services\CommandHandlers\PaymentChannel\ChannelTradeNotifyCommandHandler;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see ChannelTradeNotifyCommandHandler::handle()
 * @method tradeNotify(ChannelNotifyTradeCommand $command)
 * @method refund(ChannelRefundCreateCommand $command)
 * @method refundQuery(ChannelRefundQueryCommand $command)
 */
class PaymentChannelHandlerService extends ApplicationService
{
    public function __construct(
        public TradeRepositoryInterface       $tradeRepository,
        public RefundRepositoryInterface      $refundRepository,
        public ChannelAppRepositoryInterface  $channelAppRepository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public PaymentChannelService          $paymentChannelService,

    )
    {
    }


    protected static $macros = [
        'tradeNotify' => ChannelTradeNotifyCommandHandler::class,
        'refund'      => ChannelRefundCreateCommandHandler::class,
        'refundQuery' => ChannelRefundQueryCommandHandler::class,
    ];


}
