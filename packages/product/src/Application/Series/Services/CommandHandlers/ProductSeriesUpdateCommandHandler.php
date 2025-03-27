<?php

namespace RedJasmine\Product\Application\Series\Services\CommandHandlers;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesProductData;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ProductSeriesUpdateCommandHandler extends CommandHandler
{


    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(ProductSeriesUpdateCommand $command) : ProductSeries
    {

        $this->beginDatabaseTransaction();
        try {
            /**
             * @var $model ProductSeries
             */
            $model          = $this->getService()->getRepository()->find($command->id);
            $model->remarks = $command->remarks;
            $model->name    = $command->name;

            $model->products = Collection::make();
            if ($command->products) {
                $products = Collection::make($command->products);
                $products->each(function (ProductSeriesProductData $productSeriesProductData) use ($model) {
                    $productSeriesProduct             = new ProductSeriesProduct();
                    $productSeriesProduct->product_id = $productSeriesProductData->productId;
                    $productSeriesProduct->name       = $productSeriesProductData->name;
                    $model->products->push($productSeriesProduct);
                });
            }

            $this->getService()->getRepository()->update($model);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;

        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }


        return $model;
    }

}
