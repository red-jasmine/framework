<?php

namespace RedJasmine\Order\Application\Services\Logistics\Commands;

use RedJasmine\Order\Application\Services\Handlers\Logistics\AbstractException;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsRepositoryInterface;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class LogisticsChangeStatusCommandHandler extends CommandHandler
{

    public function __construct(
        protected OrderLogisticsReadRepositoryInterface $readRepository,
        protected OrderLogisticsRepositoryInterface     $repository
    )
    {

    }


    public function handle(LogisticsChangeStatusCommand $command) : void
    {
        // 查询物流单号

        $this->beginDatabaseTransaction();

        try {

            $logistics = $this->readRepository->getByLogisticsNo($command->logisticsCompanyCode, $command->logisticsNo);

            foreach ($logistics as $logistic) {
                $logistic->changeStatus($command->status);
                $this->repository->update($logistic);
            }

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
