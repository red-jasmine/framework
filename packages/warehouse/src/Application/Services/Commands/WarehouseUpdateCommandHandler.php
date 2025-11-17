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

    public function handle(WarehouseUpdateCommand $command): Warehouse
    {
        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->find($command->id);
            $model = $this->service->transformer->transform($command, $model);
            $this->service->repository->update($model);

            // 保存后同步市场/门店关联
            $this->service->transformer->syncMarketsAfterSave($model);

            $this->commitDatabaseTransaction();
            return $model;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}

