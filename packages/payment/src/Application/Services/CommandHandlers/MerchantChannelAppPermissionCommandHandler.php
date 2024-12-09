<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class MerchantChannelAppPermissionCommandHandler extends CommandHandler
{

    public function __construct(
        protected MerchantChannelAppPermissionRepositoryInterface $repository,
        protected MerchantRepositoryInterface                     $merchantRepository,
        protected ChannelAppRepositoryInterface                   $channelAppRepository,

    )
    {
    }

    /**
     * @param MerchantChannelAppPermissionData $command
     * @return void
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(MerchantChannelAppPermissionData $command) : void
    {


        $this->beginDatabaseTransaction();
        try {
            $this->merchantRepository->find($command->merchantId);
            $this->channelAppRepository->find($command->channelAppId);
            $permission                 = $this->repository->find($command->merchantId, $command->channelAppId);
            $permission                 = $permission ?? new MerchantChannelAppPermission();
            $permission->channel_app_id = $command->channelAppId;
            $permission->merchant_id    = $command->merchantId;
            $permission->status         = $command->status;
            $this->repository->store($permission);
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
