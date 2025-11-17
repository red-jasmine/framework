<?php

namespace RedJasmine\Product\Application\Price\Services\Commands;

use Illuminate\Database\Eloquent\Collection;
use Money\Currency;
use RedJasmine\Money\Data\Money;
use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * @property  PriceApplicationService $service
 */
class ProductPriceBulkCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected PriceApplicationService $service
    ) {
    }

    /**
     * 批量创建/更新变体价格，返回商品价格汇总
     *
     * @param  ProductPriceCreateCommand  $command
     *
     * @return ProductPrice
     * @throws Throwable
     */
    public function handle(ProductPriceCreateCommand $command) : ProductPrice
    {
        $this->beginDatabaseTransaction();

        try {

            $product      = $this->service->productRepository->find($command->productId);
            $repository   = $this->service->repository;
            $productPrice = $this->service->domainService->setProductPrices($product, $command);

            // 使用仓库保存商品价格汇总（作为聚合根，会自动保存关联的变体价格）
            if (!$productPrice->exists) {
                $repository->store($productPrice);
            } else {
                $repository->update($productPrice);
            }

            $this->commitDatabaseTransaction();

            return $productPrice;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }

}

