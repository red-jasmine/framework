<?php

namespace RedJasmine\Product\Application\Price\Services\Commands;

use Money\Currency;
use RedJasmine\Money\Data\Money;
use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Product\Domain\Price\Repositories\ProductPriceRepositoryInterface;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * @method PriceApplicationService getService()
 */
class ProductPriceDeleteCommandHandler extends CommandHandler
{
    public function __construct(
        protected ProductPriceRepositoryInterface $productPriceRepository
    ) {
    }

    /**
     * 处理变体价格删除命令
     *
     * @param ProductPriceDeleteCommand $command
     * @return void
     * @throws Throwable
     */
    public function handle(ProductPriceDeleteCommand $command): void
    {
        $this->beginDatabaseTransaction();

        try {
            $variantPrice = ProductVariantPrice::find($command->id);
            if (!$variantPrice) {
                $this->commitDatabaseTransaction();
                return;
            }

            // 保存删除前的维度信息，用于后续更新商品价格汇总
            $productId = $variantPrice->product_id;
            $market = $variantPrice->market;
            $store = $variantPrice->store;
            $userLevel = $variantPrice->user_level;
            $currency = $variantPrice->currency;

            // 删除变体价格
            $variantPrice->delete();

            // 更新商品价格汇总（重新计算最低价）
            $this->updateProductPriceSummary($productId, $market, $store, $userLevel, $currency);

            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }

    /**
     * 更新商品价格汇总（根据所有变体价格计算最低价）
     *
     * @param int $productId
     * @param string $market
     * @param string $store
     * @param string $userLevel
     * @param string $currency
     * @return void
     */
    protected function updateProductPriceSummary(
        int $productId,
        string $market,
        string $store,
        string $userLevel,
        string $currency
    ): void {
        // 查询该商品在该维度下的所有变体价格（使用维度查询作用域）
        $variantPrices = ProductVariantPrice::query()
            ->where('product_id', $productId)
            ->where('currency', $currency)
            ->byDimensions($market, $store, $userLevel)
            ->get();

        // 查找商品价格汇总
        $productPrice = $this->productPriceRepository->findByDimensions(
            $productId,
            $market,
            $store,
            $userLevel
        );

        if ($variantPrices->isEmpty()) {
            // 如果没有变体价格了，删除商品价格汇总
            if ($productPrice) {
                $this->productPriceRepository->delete($productPrice);
            }
            return;
        }

        // 计算最低价（price 是必填的）
        $minPrice = $variantPrices
            ->filter(fn($vp) => $vp->price !== null)
            ->min(function ($variantPrice) {
                return $variantPrice->price->getAmount();
            });

        // 计算最低市场价（market_price 是可选的）
        $minMarketPrice = $variantPrices
            ->filter(fn($vp) => $vp->market_price !== null)
            ->min(function ($variantPrice) {
                return $variantPrice->market_price->getAmount();
            });

        // 计算最低成本价（cost_price 是可选的）
        $minCostPrice = $variantPrices
            ->filter(fn($vp) => $vp->cost_price !== null)
            ->min(function ($variantPrice) {
                return $variantPrice->cost_price->getAmount();
            });

        $currencyObj = new Currency($currency);

        if (!$productPrice) {
            // 创建新的商品价格汇总
            $productPrice = new ProductPrice();
            $productPrice->product_id = $productId;
            $productPrice->market = $market;
            $productPrice->store = $store;
            $productPrice->user_level = $userLevel;
            $productPrice->currency = $currency;
            $productPrice->price = $minPrice ? Money::parse((string)$minPrice, $currencyObj) : null;
            $productPrice->market_price = $minMarketPrice ? Money::parse((string)$minMarketPrice, $currencyObj) : null;
            $productPrice->cost_price = $minCostPrice ? Money::parse((string)$minCostPrice, $currencyObj) : null;

            $this->productPriceRepository->store($productPrice);
        } else {
            // 更新现有商品价格汇总
            $productPrice->currency = $currency;
            $productPrice->price = $minPrice ? Money::parse((string)$minPrice, $currencyObj) : null;
            $productPrice->market_price = $minMarketPrice ? Money::parse((string)$minMarketPrice, $currencyObj) : null;
            $productPrice->cost_price = $minCostPrice ? Money::parse((string)$minCostPrice, $currencyObj) : null;

            $this->productPriceRepository->update($productPrice);
        }
    }
}

