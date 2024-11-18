<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Domain\Models\PaymentMerchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\MerchantTransformer;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use Throwable;

class MerchantCreateCommandHandle extends CommandHandler
{


    public function __construct(protected MerchantRepositoryInterface $repository)
    {
    }

    /**
     * @param MerchantCreateCommand $command
     * @return PaymentMerchant
     * @throws Throwable
     */
    public function handle(MerchantCreateCommand $command) : PaymentMerchant
    {
        $this->beginDatabaseTransaction();

        try {

            $model = app(MerchantTransformer::class)->transform($command);

            $this->repository->store($model);

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
