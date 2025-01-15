<?php

namespace RedJasmine\Payment\Application\Services\ChannelApp\Commands;

use RedJasmine\Payment\Application\Services\ChannelApp\ChannelAppCommandService;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelMerchantRepositoryInterface;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ChannelAppCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected ChannelAppCommandService $service,
        protected ChannelMerchantRepositoryInterface $merchantRepository,
    ) {
    }

    public function handle(ChannelAppCreateCommand $command) : ChannelApp
    {
        $this->beginDatabaseTransaction();

        try {
            // 渠道商户
            $channelMerchant                        = $this->merchantRepository->find($command->systemChannelMerchantId);
            $channelApp                             = ChannelApp::make();
            $channelApp->owner                      = $channelMerchant->owner;
            $channelApp->system_channel_merchant_id = $channelMerchant->id;
            $channelApp->channel_code               = $channelMerchant->channel_code;
            $this->service->transformer->transform($command, $channelApp);
            $this->service->repository->store($channelApp);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $channelApp;

    }

}
