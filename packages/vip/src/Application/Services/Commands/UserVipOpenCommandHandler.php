<?php

namespace RedJasmine\Vip\Application\Services\Commands;

use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Vip\Application\Services\UserVipCommandService;
use RedJasmine\Vip\Domain\Exceptions\VipException;
use Throwable;

class UserVipOpenCommandHandler extends CommandHandler
{

    public function __construct(
        public UserVipCommandService $service
    ) {
    }

    /**
     * @param  UserVipOpenCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws VipException
     */
    public function handle(UserVipOpenCommand $command) : bool
    {
        $this->beginDatabaseTransaction();

        try {

            $userVip = $this->service->domainService->open($command);

            $this->service->repository->update($userVip);

            $orders = $this->service->domainService->getOrders();

            $this->service->userVipOrderRepository->stores($orders);
            $this->service->domainService->flushOrders();

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return true;

    }

}