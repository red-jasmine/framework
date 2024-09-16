<?php

namespace RedJasmine\Product\Application\Series\Services\CommandHandlers;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesProductData;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Support\Application\CommandHandler;

class ProductSeriesDeleteCommandHandler extends CommandHandler
{


    public function handle(ProductSeriesDeleteCommand $command) : ProductSeries
    {
        // TODO
        /**
         * @var $model ProductSeries
         */
        $model = $this->getService()->getRepository()->find($command->id);
        $this->execute(
            execute: null,
            persistence: fn() => $this->getService()->getRepository()->delete($model)
        );
        return $model;
    }

}
