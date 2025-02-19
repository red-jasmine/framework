<?php

namespace RedJasmine\Vip\Application\Services\Commands;

use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Vip\Application\Services\UserVipCommandService;
use Throwable;

class UserVipOpenCommandHandler extends CommandHandler
{

    public function __construct(
        public UserVipCommandService $service
    ) {
    }

    public function handle(UserVipOpenCommand $command)
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

    }

}