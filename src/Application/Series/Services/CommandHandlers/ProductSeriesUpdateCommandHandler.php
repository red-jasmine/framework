<?php

namespace RedJasmine\Product\Application\Series\Services\CommandHandlers;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesProductData;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Facades\ServiceContext;

class ProductSeriesUpdateCommandHandler extends CommandHandler
{


    public function handle(ProductSeriesUpdateCommand $command) : ProductSeries
    {
        // TODO
        /**
         * @var $model ProductSeries
         */
        $model          = $this->getService()->getRepository()->find($command->id);
        $model->remarks = $command->remarks;
        $model->name    = $command->name;
        $model->updater = ServiceContext::getOperator();

        $model->products = Collection::make([]);

        $command->products->each(function (ProductSeriesProductData $productSeriesProductData) use ($model) {
            $productSeriesProduct             = new ProductSeriesProduct();
            $productSeriesProduct->product_id = $productSeriesProductData->productId;
            $productSeriesProduct->name       = $productSeriesProductData->name;
            $model->products->push($productSeriesProduct);
        });


        $this->execute(
            execute: null,
            persistence: fn() => $this->getService()->getRepository()->update($model)
        );
        return $model;
    }

}
