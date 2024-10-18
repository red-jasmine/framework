<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use JsonException;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Group\Services\ProductGroupQueryService;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Property\Services\PropertyValidateService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\PropertyFormatter;
use RedJasmine\Product\Domain\Product\Transformer\ProductTransformer;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\CommandHandler;
use Throwable;

/**
 * @method  ProductCommandService getService()
 */
class ProductSetStatusCommandHandler extends CommandHandler
{


    public function __construct(
        protected BrandQueryService           $brandQueryService,
        protected StockCommandService         $stockCommandService,
        protected PropertyFormatter           $propertyFormatter,
        protected PropertyValidateService     $propertyValidateService,
        protected ProductCategoryQueryService $categoryQueryService,
        protected ProductGroupQueryService    $groupQueryService,
        protected ProductTransformer          $productTransformer
    )
    {


    }


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


            $product->modified_time = now();

            $this->getService()->getRepository()->update($product);

            $this->commitDatabaseTransaction();

            return $product;
        } catch (Throwable $throwable) {

            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

    protected function validate($command) : void
    {

    }


}
