<?php

namespace RedJasmine\Warehouse\Application\Services\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Warehouse\Application\Services\WarehouseApplicationService;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use Throwable;

class WarehouseCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected WarehouseApplicationService $service
    ) {
    }

    public function handle(WarehouseCreateCommand $command): Warehouse
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->newModel();
            $model = $this->service->transformer->transform($command, $model);
            $this->service->repository->store($model);



            $this->commitDatabaseTransaction();
            return $model;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}

