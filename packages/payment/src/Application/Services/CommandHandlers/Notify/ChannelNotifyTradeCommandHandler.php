<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Notify;

use RedJasmine\Payment\Application\Commands\Notify\ChannelNotifyTradeCommand;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Support\Application\CommandHandler;

class ChannelNotifyTradeCommandHandler extends CommandHandler
{
    public function __construct(
        protected TradeRepositoryInterface      $repository,
        protected ChannelAppRepositoryInterface $channelAppRepository,
        protected MerchantAppRepository         $merchantAppRepository,


    )
    {
    }

    public function handle(ChannelNotifyTradeCommand $command)
    {
        $channelApp       = $this->channelAppRepository->find($command->appId);
        $channelTradeData = app(PaymentChannelService::class)->completePurchase($channelApp, $command->content);


        // 获取
        $channelTradeData->id;

        $trade = $this->repository->find($channelTradeData->id);
        $trade->paid($channelTradeData);
    }

}
