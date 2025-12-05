<?php

namespace RedJasmine\Payment\Application\Services\Merchant\Commands;

use RedJasmine\Payment\Application\Services\Merchant\MerchantCommandService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
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
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

}
