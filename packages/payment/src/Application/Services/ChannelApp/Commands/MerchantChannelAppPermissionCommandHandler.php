<?php

namespace RedJasmine\Payment\Application\Services\ChannelApp\Commands;

use RedJasmine\Payment\Application\Services\ChannelApp\ChannelAppCommandService;
use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class MerchantChannelAppPermissionCommandHandler extends CommandHandler
{

    public function __construct(protected ChannelAppCommandService $service)
    {
    }

    /**
     * @param MerchantChannelAppPermissionData $command
     *
     * @return void
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(MerchantChannelAppPermissionData $command) : void
    {


        $this->beginDatabaseTransaction();
        try {

            $this->service->merchantAppRepository->find($command->merchantAppId);
            $this->service->repository->find($command->channelAppId);
            $permission                  = $this->service
                ->permissionRepository->find($command->merchantAppId, $command->channelAppId);
            $permission                  = $permission ?? MerchantChannelAppPermission::make();
            $permission->merchant_app_id = $command->merchantAppId;
            $permission->channel_app_id  = $command->channelAppId;
            $permission->status          = $command->status;
            $this->service->permissionRepository->store($permission);
            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }

}
