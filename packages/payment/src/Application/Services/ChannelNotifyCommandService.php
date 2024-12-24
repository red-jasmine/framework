<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\Notify\ChannelNotifyTradeCommandHandler;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @see ChannelNotifyTradeCommandHandler::handle()
 * @method tradeNotify(ChannelNotifyTradeCommand $command)
 */
class ChannelNotifyCommandService extends Service
{
    public function __construct(
        public TradeRepositoryInterface $tradeRepository,
        public ChannelAppRepositoryInterface $channelAppRepository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public PaymentChannelService $paymentChannelService,
    ) {
    }


    protected static $macros = [
        'tradeNotify' => ChannelNotifyTradeCommandHandler::class,
    ];


}
