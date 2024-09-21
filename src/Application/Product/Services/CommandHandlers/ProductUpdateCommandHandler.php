<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use JsonException;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Exceptions\StockException;
use Throwable;
use function Symfony\Component\String\u;

/**
 * @method  ProductCommandService getService()
 */
class ProductUpdateCommandHandler extends ProductCommandHandler
{


    /**
     * @param  ProductUpdateCommand  $command
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


        /**
         * @var $product Product
         */
        $product = $this->getService()->getRepository()->find($command->id);




        $this->beginDatabaseTransaction();
        try {

            $product->setRelation('skus', $product->skus()->withTrashed()->get());

            $product->skus->each(function ($sku) {
                $sku->setDeleted();
            });

            $this->getService()->hook('update.validate', $command, fn() => $this->validate($command));

            $product = $this->getService()->hook('update.fill', $command,
                fn() => $this->productTransformer->transform($product, $command));


            $this->handleStock($product, $command);


            $this->commitDatabaseTransaction();

            return $product;
        } catch (Throwable $throwable) {

            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }


}
