<?php

namespace RedJasmine\Product\Application\Series\Services\CommandHandlers;

use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesProductData;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;

class ProductSeriesCreateCommandHandler extends CommandHandler
{


    /**
     * @throws AbstractException
     * @throws \Throwable
     */
    public function handle(ProductSeriesCreateCommand $command) : ProductSeries
    {

        $this->beginDatabaseTransaction();
        try {

            /**
             * @var $model ProductSeries
             */
            $model          = $this->getService()->newModel();
            $model->owner   = $command->owner;
            $model->remarks = $command->remarks;
            $model->name    = $command->name;

            // 验证重复
            if ($command->products->count() !== $command->products->pluck('productId')->unique()->count()) {
                throw new ProductException('商品重复');
            }

            $command->products->each(function (ProductSeriesProductData $productSeriesProductData) use ($model) {
                $productSeriesProduct             = new ProductSeriesProduct();
                $productSeriesProduct->product_id = $productSeriesProductData->productId;
                $productSeriesProduct->name       = $productSeriesProductData->name;
                $model->products->push($productSeriesProduct);
            });

            $this->getService()->getRepository()->store($model);


            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;

        }


        return $model;
    }

}
