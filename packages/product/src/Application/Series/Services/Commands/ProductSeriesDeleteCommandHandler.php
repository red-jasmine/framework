<?php

namespace RedJasmine\Product\Application\Series\Services\Commands;

use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class ProductSeriesDeleteCommandHandler extends CommandHandler
{

    public function __construct(
        protected ProductSeriesApplicationService $service
    )
    {
    }
    /**
     * @throws Throwable
     */
    public function handle(ProductSeriesDeleteCommand $command) : ProductSeries
    {
        $this->beginDatabaseTransaction();
        try {
            /**
             * @var $model ProductSeries
             */
            $model = $this->service->repository->find($command->id);
            $this->service->repository->delete($model);
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollbackDatabaseTransaction();
            throw $throwable;
        }

        return $model;
    }

}
