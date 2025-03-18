<?php

namespace RedJasmine\Payment\Application\Services\PaymentChannel;

use RedJasmine\Payment\Application\Services\AsyncNotify\Commands\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundCreateCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelRefundQueryCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelTradeNotifyCommandHandler;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelTransferCreateCommand;
use RedJasmine\Payment\Application\Services\PaymentChannel\Commands\ChannelTransferQueryCommand;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @see ChannelTradeNotifyCommandHandler::handle()
 * @method tradeNotify(ChannelNotifyTradeCommand $command)
 * @method refund(ChannelRefundCreateCommand $command)
 * @method refundQuery(ChannelRefundQueryCommand $command)
 * @method transfer(ChannelTransferCreateCommand $command)
 * @method transferQuery(ChannelTransferQueryCommand $command)
 */
class PaymentChannelHandlerService extends Service
{
    public function __construct(
        public TradeRepositoryInterface $tradeRepository,
        public RefundRepositoryInterface $refundRepository,
        public TransferRepositoryInterface $transferRepository,
        public ChannelAppRepositoryInterface $channelAppRepository,
        public ChannelProductRepositoryInterface $channelProductRepository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public PaymentChannelService $paymentChannelService,

    ) {
    }


    protected static $macros = [
        'refund'        => Commands\ChannelRefundCreateCommandHandler::class, // 异步发起退款
        'refundQuery'   => Commands\ChannelRefundQueryCommandHandler::class, // 发起退款查询
        'tradeNotify'   => Commands\ChannelTradeNotifyCommandHandler::class, // 接受交易通知
        'transfer'      => Commands\ChannelTransferCreateCommandHandler::class, // 接受交易通知
        'transferQuery' => Commands\ChannelTransferQueryCommandHandler::class, // 接受交易通知

    ];


}
