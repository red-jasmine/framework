<?php

namespace RedJasmine\Payment\Application\Services\Transfer\Commands;

use RedJasmine\Payment\Application\Services\Transfer\TransferApplicationService;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Factories\TransferFactory;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Services\ChannelAppPermissionService;
use RedJasmine\Payment\Domain\Services\Routing\TransferRoutingService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TransferCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected TransferApplicationService $service,
        protected ChannelAppPermissionService $channelAppPermissionService,
        protected TransferRoutingService $transferRoutingService,
    ) {
    }

    /**
     * @param  TransferCreateCommand  $command
     *
     * @return Transfer
     * @throws AbstractException
     * @throws Throwable
     * @throws PaymentException
     */
    public function handle(TransferCreateCommand $command) : Transfer
    {
        $this->beginDatabaseTransaction();

        try {
            $merchantApp = $this->service->merchantAppRepository->find($command->merchantAppId);

            $transfer = app(TransferFactory::class)->create($command);
            // 绑定商户应用
            $transfer->merchant_id     = $merchantApp->merchant_id;
            $transfer->merchant_app_id = $merchantApp->id;
            // 设置渠道应用
            $transfer->method_code    = $command->methodCode;
            $transfer->channel_app_id = $command->channelAppId;
            // 路由渠道应用
            $channelApp     = $this->transferRoutingService->getChannelApp($transfer);
            $channelProduct = $this->transferRoutingService->getChannelProduct($transfer, $channelApp);

            $transfer->setChannelApp($channelApp, $channelProduct);

            if ($command->isAutoExecute) {
                $transfer->executing();
            }

            $this->service->repository->store($transfer);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $transfer;

    }

}
