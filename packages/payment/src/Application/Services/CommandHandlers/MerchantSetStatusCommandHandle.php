<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantSetStatusCommand;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use Throwable;

class MerchantSetStatusCommandHandle extends CommandHandler
{


    public function __construct(protected MerchantRepositoryInterface $repository)
    {
    }

    /**
     * @param MerchantSetStatusCommand $command
     * @return void
     * @throws Throwable
     */
    public function handle(MerchantSetStatusCommand $command):void
    {
        $this->beginDatabaseTransaction();

        try {

            $model = $this->repository->find($command->id);
            $model->setStatus($command->status);
            $this->repository->update($model);

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
