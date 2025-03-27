<?php

namespace RedJasmine\Product\Application\Series\Services\CommandHandlers;

use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class ProductSeriesDeleteCommandHandler extends CommandHandler
{


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
            $model = $this->getService()->getRepository()->find($command->id);
            $this->getService()->getRepository()->delete($model);
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollbackDatabaseTransaction();
            throw $throwable;
        }

        return $model;
    }

}
