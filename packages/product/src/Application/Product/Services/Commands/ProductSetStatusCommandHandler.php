<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use JsonException;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryApplicationService;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\AttributeFormatter;
use RedJasmine\Product\Domain\Product\Transformer\ProductTransformer;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\ProductAttributeException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * @method  ProductApplicationService getService()
 */
class ProductSetStatusCommandHandler extends CommandHandler
{


    public function __construct(
        protected ProductApplicationService $service,
        protected StockApplicationService $stockCommandService,
        protected AttributeFormatter $attributeFormatter,
        protected ProductAttributeValidateService $attributeValidateService,
        protected ProductCategoryApplicationService $categoryQueryService,
        protected ProductGroupApplicationService $groupQueryService,
        protected ProductTransformer $productTransformer
    ) {


    }


    /**
     * @param  ProductSetStatusCommand  $command
     *
     * @return Product|null
     * @throws Throwable
     * @throws JsonException
     * @throws ProductException
     * @throws ProductAttributeException
     * @throws StockException
     */
    public function handle(ProductSetStatusCommand $command) : ?Product
    {


        /**
         * @var $product Product
         */
        $product = $this->service->repository->find($command->id);


        $this->beginDatabaseTransaction();
        try {


            $product->status = $command->status;

            $this->service->hook('update.validate', $command, fn() => $this->validate($command));


            $product->modified_time = now();

            $this->service->repository->update($product);

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
