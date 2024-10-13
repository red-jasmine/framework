<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use JsonException;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Exceptions\StockException;
use Throwable;

/**
 * @method  ProductCommandService getService()
 */
class ProductSetStatusCommandHandler extends ProductCommandHandler
{


    /**
     * @param ProductSetStatusCommand $command
     *
     * @return Product|null
     * @throws Throwable
     * @throws JsonException
     * @throws ProductException
     * @throws ProductPropertyException
     * @throws StockException
     */
    public function handle(ProductSetStatusCommand $command) : ?Product
    {


        /**
         * @var $product Product
         */
        $product = $this->getService()->getRepository()->find($command->id);


        $this->beginDatabaseTransaction();
        try {


            $product->status = $command->status;

            $this->getService()->hook('update.validate', $command, fn() => $this->validate($command));

            $this->getRepository()->update($product);

            $this->commitDatabaseTransaction();

            return $product;
        } catch (Throwable $throwable) {

            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }


}
