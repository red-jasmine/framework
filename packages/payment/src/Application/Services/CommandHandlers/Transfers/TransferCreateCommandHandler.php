<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers\Transfers;

use RedJasmine\Payment\Application\Commands\Transfer\TransferCreateCommand;
use RedJasmine\Payment\Application\Services\TransferCommandService;
use RedJasmine\Payment\Domain\Factories\TransferFactory;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class TransferCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected TransferCommandService $service
    ) {
    }

    public function handle(TransferCreateCommand $command)
    {
        $this->beginDatabaseTransaction();

        try {
            $merchantApp = $this->service->merchantAppRepository->find($command->merchantAppId);

            $transfer                  = app(TransferFactory::class)->create($command);
            $transfer->merchant_id     = $merchantApp->merchant_id;
            $transfer->merchant_app_id = $merchantApp->id;

            $this->service->repository->store($transfer);

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
