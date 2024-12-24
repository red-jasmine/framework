<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantUpdateCommand;
use RedJasmine\Payment\Application\Services\MerchantCommandService;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\MerchantTransformer;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use Throwable;

class MerchantUpdateCommandHandle extends CommandHandler
{

    public function __construct(protected MerchantCommandService $service)
    {

    }


    /**
     * @param  MerchantUpdateCommand  $command
     *
     * @return Merchant
     * @throws Throwable
     */
    public function handle(MerchantUpdateCommand $command) : Merchant
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->find($command->id);

            $model = $this->service->transformer->transform($command, $model);

            $this->service->repository->update($model);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $model;

    }

}
