<?php

namespace RedJasmine\Payment\Application\Services\Settle\Commands;

use RedJasmine\Payment\Application\Services\Settle\SettleCommandService;
use RedJasmine\Payment\Domain\Data\SettleData;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Services\SettleService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class SettleCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected SettleCommandService $service,
        public SettleService           $settleService,
    )
    {
    }

    public function handle(SettleCreateCommand $command) : Settle
    {
        $this->beginDatabaseTransaction();

        try {

            $settle = $this->settleService->create($command);

            $this->service->repository->store($settle);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $settle;

    }
}
