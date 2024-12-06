<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantUpdateCommand;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\MerchantTransformer;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use Throwable;

class MerchantUpdateCommandHandle extends CommandHandler
{


    public function __construct(protected MerchantRepositoryInterface $repository)
    {
    }

    /**
     * @param MerchantUpdateCommand $command
     * @return Merchant
     * @throws Throwable
     */
    public function handle(MerchantUpdateCommand $command) : Merchant
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->repository->find($command->id);

            $model = app(MerchantTransformer::class)->transform($command, $model);

            $this->repository->update($model);

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
