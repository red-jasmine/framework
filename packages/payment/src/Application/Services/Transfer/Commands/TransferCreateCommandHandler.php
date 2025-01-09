<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use RedJasmine\Payment\Application\Services\Transfer\TransferCommandService;
use RedJasmine\Payment\Domain\Factories\TransferFactory;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;
use RedJasmine\Payment\Domain\Services\Routing\TransferRoutingService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TransferCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected TransferCommandService $service,
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected TransferRoutingService $transferRoutingService,
    ) {
    }

    public function handle(TransferCreateCommand $command)
    {
        $this->beginDatabaseTransaction();

        try {
            $merchantApp = $this->service->merchantAppRepository->find($command->merchantAppId);
            $transfer    = app(TransferFactory::class)->create($command);
            // 绑定商户应用
            $transfer->merchant_id     = $merchantApp->merchant_id;
            $transfer->merchant_app_id = $merchantApp->id;
            // 设置渠道应用
            $transfer->channel_app_id = $command->channelAppId;
            $transfer->channel_app_id = $command->channelAppId;
            // 路由转账产品 TODO
            $this->transferRoutingService->getChannelApp($transfer, $merchantApp);
            $this->service->repository->store($transfer);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }

}
