<?php

namespace RedJasmine\Product\Application\Series\Services\CommandHandlers;

use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesProductData;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Facades\ServiceContext;

class ProductSeriesCreateCommandHandler extends CommandHandler
{


    public function handle(ProductSeriesCreateCommand $command) : ProductSeries
    {
        // TODO
        /**
         * @var $model ProductSeries
         */
        $model          = $this->getService()->newModel();
        $model->owner   = $command->owner;
        $model->creator = ServiceContext::getOperator();
        $model->remarks = $command->remarks;
        $model->name    = $command->name;

        // 验证重复

        if ($command->products->count() !== $command->products->pluck('productId')->unique()->count()) {
            throw new \RuntimeException('Products must have product identically');
        }
        // 验证商品是否存在 TODO

        $command->products->each(function (ProductSeriesProductData $productSeriesProductData) use ($model) {
            $productSeriesProduct             = new ProductSeriesProduct();
            $productSeriesProduct->product_id = $productSeriesProductData->productId;
            $productSeriesProduct->name       = $productSeriesProductData->name;
            $model->products->push($productSeriesProduct);
        });


        $this->execute(
            execute: null,
            persistence: fn() => $this->getService()->getRepository()->store($model)
        );
        return $model;
    }

}
