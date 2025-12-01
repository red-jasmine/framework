<?php

namespace RedJasmine\Product\Application\Series\Services\Commands;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ProductSeriesUpdateCommandHandler extends CommandHandler
{


    public function __construct(
        protected ProductSeriesApplicationService $service
    )
    {
    }

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
            $model          = $this->service->repository->find($command->id);
            $model->remarks = $command->remarks;
            $model->name    = $command->name;

            $model->products = Collection::make();


            if ($command->products) {
                $products = Collection::make($command->products);
                $products->each(function (ProductSeriesProductData $productSeriesProductData, $index) use ($model) {
                    $productSeriesProduct             = new ProductSeriesProduct();
                    $productSeriesProduct->product_id = $productSeriesProductData->productId;
                    $productSeriesProduct->position   = $productSeriesProductData->position ?? $index;
                    $model->products->push($productSeriesProduct);
                });
            }

            $this->service->repository->update($model);

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
