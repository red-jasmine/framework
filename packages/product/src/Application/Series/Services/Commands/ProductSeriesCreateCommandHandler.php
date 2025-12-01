<?php

namespace RedJasmine\Product\Application\Series\Services\Commands;

use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class ProductSeriesCreateCommandHandler extends CommandHandler
{

    public function __construct(protected ApplicationService $service)
    {
        $this->context = new HandleContext();
    }


    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(ProductSeriesCreateCommand $command) : ProductSeries
    {

        $this->beginDatabaseTransaction();
        try {

            /**
             * @var $model ProductSeries
             */
            $model          = $this->service->newModel();
            $model->owner   = $command->owner;
            $model->remarks = $command->remarks;
            $model->name    = $command->name;
            if ($command->products) {
                $products = collect($command->products);
                // 验证重复
                if ($products->count() !== $products->pluck('productId')->unique()->count()) {
                    throw new ProductException('商品重复');
                }

                $products->each(function (ProductSeriesProductData $productSeriesProductData, $index) use ($model) {
                    $productSeriesProduct             = new ProductSeriesProduct();
                    $productSeriesProduct->product_id = $productSeriesProductData->productId;
                    $productSeriesProduct->position   = $productSeriesProductData->position ?? $index;
                    $model->products->push($productSeriesProduct);
                });
            }


            $this->service->repository->store($model);


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
