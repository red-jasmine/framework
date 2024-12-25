<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Merchants;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\AbstractException;
use RedJasmine\Payment\Application\Services\MerchantCommandService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use Throwable;

class MerchantSetStatusCommandHandle extends CommandHandler
{
    public function __construct(protected MerchantCommandService $service)
    {

    }

    /**
     * @param  MerchantSetStatusCommand  $command
     *
     * @return void
     * @throws Throwable
     */
    public function handle(MerchantSetStatusCommand $command) : void
    {
        $this->beginDatabaseTransaction();

        try {

            $model = $this->service->repository->find($command->id);
            $model->setStatus($command->status);
            $this->service->repository->update($model);

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
