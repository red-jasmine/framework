<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use JsonException;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Exceptions\StockException;
use Throwable;

/**
 * @method  ProductApplicationService getService()
 */
class ProductUpdateCommandHandler extends ProductCommandHandler
{


    /**
     * @param ProductUpdateCommand $command
     *
     * @return Product|null
     * @throws Throwable
     * @throws JsonException
     * @throws ProductException
     * @throws ProductPropertyException
     * @throws StockException
     */
    public function handle(ProductUpdateCommand $command) : ?Product
    {





        $this->beginDatabaseTransaction();
        try {
            $product = $this->service->repository->find($command->id);
            $product->setRelation('skus', $product->skus()->withTrashed()->get());

            $product->skus->each(function ($sku) {
                $sku->setDeleted();
            });

            $this->service->hook('update.validate', $command, fn() => $this->validate($command));

            $product = $this->service->hook('update.fill', $command,
                fn() => $this->productTransformer->transform($product, $command));

            $product->modified_time = now();

            $this->service->repository->update($product);

            $this->handleStock($product, $command);

            $this->commitDatabaseTransaction();

            return $product;
        } catch (Throwable $throwable) {

            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }


}
