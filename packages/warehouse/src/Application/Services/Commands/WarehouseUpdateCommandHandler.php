<?php

namespace RedJasmine\Warehouse\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Warehouse\Application\Services\WarehouseApplicationService;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use Throwable;

class WarehouseUpdateCommandHandler extends CommandHandler
{
    public function __construct(
        protected WarehouseApplicationService $service
    ) {
    }

    /**
     * @param  WarehouseUpdateCommand  $command
     *
     * @return Warehouse
     * @throws Throwable
     */
    public function handle(WarehouseUpdateCommand $command) : Warehouse
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->find($command->id);
            $model = $this->service->transformer->transform($command, $model);
            $this->service->repository->update($model);
            $this->commitDatabaseTransaction();
            return $model;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}

