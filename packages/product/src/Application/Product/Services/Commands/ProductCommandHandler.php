<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;


use RedJasmine\Product\Application\Brand\Services\BrandApplicationService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryApplicationService;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Application\Property\Services\PropertyValidateService;
use RedJasmine\Product\Application\Stock\Services\Commands\StockCommand;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\PropertyFormatter;
use RedJasmine\Product\Domain\Product\Transformer\ProductTransformer;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductException;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * @method  ProductApplicationService getService()
 */
class ProductCommandHandler extends CommandHandler
{

    public function __construct(
        public ProductApplicationService $service,
        protected BrandApplicationService $brandQueryService,
        protected StockApplicationService $stockCommandService,
        protected PropertyFormatter $propertyFormatter,
        protected PropertyValidateService $propertyValidateService,
        protected ProductCategoryApplicationService $categoryQueryService,
        protected ProductGroupApplicationService $groupQueryService,
        protected ProductTransformer $productTransformer
    ) {


    }

    /**
     * @param  Product  $product
     * @param  \RedJasmine\Product\Domain\Product\Data\Product  $command
     *
     * @return void
     * @throws StockException
     * @throws Throwable
     */
    protected function handleStock(Product $product, \RedJasmine\Product\Domain\Product\Data\Product $command) : void
    {


        $skuCommand = $command->skus?->keyBy('properties');

        foreach ($product->skus as $sku) {

            // 修改库存 把 删除的库存设置为 0
            if ($sku->deleted_at) {
                $stock = 0;
            } else {
                $stock = $skuCommand[$sku->properties]?->stock ?? $command->stock;
            }

            $stockCommand              = new StockCommand();
            $stockCommand->productId   = $sku->product_id;
            $stockCommand->actionType  = ProductStockActionTypeEnum::RESET;
            $stockCommand->skuId       = $sku->id;
            $stockCommand->actionStock = $stock;
            $stockCommand->changeType  = ProductStockChangeTypeEnum::SELLER;

            // 设置库存
            $this->stockCommandService->reset($stockCommand);
        }
    }

    /**
     * @param  \RedJasmine\Product\Domain\Product\Data\Product  $command
     *
     * @return void
     * @throws ProductException
     */
    protected function validate(\RedJasmine\Product\Domain\Product\Data\Product $command) : void
    {

        $this->validateBrand($command);

        $this->validateCategory($command);

        $this->validateSellerCategory($command);

    }

    /**
     * @param  \RedJasmine\Product\Domain\Product\Data\Product  $command
     *
     * @return void
     * @throws ProductException
     */
    protected function validateBrand(\RedJasmine\Product\Domain\Product\Data\Product $command) : void
    {
        try {
            if ($command->brandId && !$this->brandQueryService->isAllowUse($command->brandId)) {
                throw new ProductException('品牌不可使用');
            }
        } catch (Throwable $exception) {
            throw new ProductException('品牌不可使用');
        }
    }

    /**
     * @param  \RedJasmine\Product\Domain\Product\Data\Product  $command
     *
     * @return void
     * @throws ProductException
     */
    protected function validateCategory(\RedJasmine\Product\Domain\Product\Data\Product $command) : void
    {

        try {
            if ($command->categoryId && !$this->categoryQueryService->isAllowUse($command->categoryId)) {
                throw new ProductException('类目不可使用');
            }
        } catch (Throwable $exception) {
            throw new ProductException('类目不可使用');
        }
    }

    /**
     * @param  \RedJasmine\Product\Domain\Product\Data\Product  $command
     *
     * @return void
     * @throws ProductException
     */
    protected function validateSellerCategory(\RedJasmine\Product\Domain\Product\Data\Product $command) : void
    {


        try {

            if ($command->productGroupId
                && !$this->groupQueryService->isAllowUse($command->productGroupId, $command->owner)) {
                throw new ProductException('商品分组不可使用');
            }
        } catch (Throwable $exception) {
            throw new ProductException('商品分组不可使用');
        }
    }
}
