<?php

namespace RedJasmine\Product\Application\Price\Services\Commands;

use Money\Currency;
use RedJasmine\Money\Data\Money;
use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * @method PriceApplicationService $service
 */
class ProductPriceBulkCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected PriceApplicationService $service
    )
    {
    }

    /**
     * 批量创建/更新变体价格，返回商品价格汇总
     *
     * @param ProductPriceCreateCommand $command
     * @return ProductPrice
     * @throws Throwable
     */
    public function handle(ProductPriceCreateCommand $command): ProductPrice
    {
        $this->beginDatabaseTransaction();

        try {
            $service = $this->service;
            $repository = $service->repository;
            $currency = new Currency($command->currency);

            // 查找或创建商品价格汇总
            $productPrice = $repository->findByDimensions(
                $command->productId,
                $command->market,
                $command->store,
                $command->userLevel
            );

            if (!$productPrice) {
                $productPrice = new ProductPrice();
                $productPrice->product_id = $command->productId;
                $productPrice->market = $command->market;
                $productPrice->store = $command->store;
                $productPrice->user_level = $command->userLevel;
                $productPrice->currency = $command->currency;
            }

            // 创建/更新变体价格并关联到 ProductPrice
            $variantPrices = [];
            foreach ($command->variants as $variantData) {
                $variantId = is_array($variantData) ? $variantData['variantId'] : $variantData->variantId;
                $price = is_array($variantData) ? ($variantData['price'] ?? 0) : ($variantData->price ?? 0);
                $marketPrice = is_array($variantData) ? ($variantData['marketPrice'] ?? null) : ($variantData->marketPrice ?? null);
                $costPrice = is_array($variantData) ? ($variantData['costPrice'] ?? null) : ($variantData->costPrice ?? null);

                // 转换 Money 对象
                $priceMoney = $price instanceof Money ? $price : Money::parse((string)$price, $currency);
                $marketPriceMoney = $marketPrice ? ($marketPrice instanceof Money ? $marketPrice : Money::parse((string)$marketPrice, $currency)) : null;
                $costPriceMoney = $costPrice ? ($costPrice instanceof Money ? $costPrice : Money::parse((string)$costPrice, $currency)) : null;

                // 查找或创建变体价格
                $variantPrice = ProductVariantPrice::query()
                    ->where('product_id', $command->productId)
                    ->where('variant_id', $variantId)
                    ->byDimensions($command->market, $command->store, $command->userLevel)
                    ->where('currency', $command->currency)
                    ->orderBy('priority', 'desc')
                    ->first();

                if (!$variantPrice) {
                    $variantPrice = new ProductVariantPrice();
                    $variantPrice->product_id = $command->productId;
                    $variantPrice->variant_id = $variantId;
                    $variantPrice->market = $command->market;
                    $variantPrice->store = $command->store;
                    $variantPrice->user_level = $command->userLevel;
                    $variantPrice->currency = $command->currency;
                }

                $variantPrice->price = $priceMoney;
                $variantPrice->market_price = $marketPriceMoney;
                $variantPrice->cost_price = $costPriceMoney;
                $variantPrice->quantity_tiers = $command->quantityTiers;
                $variantPrice->priority = $command->priority;

                $variantPrices[] = $variantPrice;
            }

            // 通过 ProductPrice 关联设置变体价格
            if ($productPrice->exists) {
                // 如果 ProductPrice 已存在，通过关联保存变体价格
                foreach ($variantPrices as $variantPrice) {
                    $productPrice->variantPrices()->save($variantPrice);
                }
            } else {
                // 如果 ProductPrice 不存在，设置关联（后续通过 push 一起保存）
                $productPrice->setRelation('variantPrices', collect($variantPrices));
            }

            // 计算并更新商品价格汇总（所有变体的最低价）
            $this->updateProductPriceSummary($productPrice, $command->currency);

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

    /**
     * 更新商品价格汇总（根据所有变体价格计算最低价）
     *
     * @param ProductPrice $productPrice
     * @param string $currency
     * @return void
     */
    protected function updateProductPriceSummary(ProductPrice $productPrice, string $currency): void
    {
        // 查询该商品在该维度下的所有变体价格
        $variantPrices = ProductVariantPrice::query()
            ->where('product_id', $productPrice->product_id)
            ->where('currency', $currency)
            ->byDimensions($productPrice->market, $productPrice->store, $productPrice->user_level)
            ->get();

        if ($variantPrices->isEmpty()) {
            $productPrice->price = null;
            $productPrice->market_price = null;
            $productPrice->cost_price = null;
            return;
        }

        // 计算最低价
        $minPrice = $variantPrices
            ->filter(fn($vp) => $vp->price !== null)
            ->min(fn($vp) => $vp->price->getAmount());

        $minMarketPrice = $variantPrices
            ->filter(fn($vp) => $vp->market_price !== null)
            ->min(fn($vp) => $vp->market_price->getAmount());

        $minCostPrice = $variantPrices
            ->filter(fn($vp) => $vp->cost_price !== null)
            ->min(fn($vp) => $vp->cost_price->getAmount());

        // 更新商品价格汇总
        $currencyObj = new Currency($currency);
        $productPrice->currency = $currency;
        $productPrice->price = $minPrice ? Money::parse((string)$minPrice, $currencyObj) : null;
        $productPrice->market_price = $minMarketPrice ? Money::parse((string)$minMarketPrice, $currencyObj) : null;
        $productPrice->cost_price = $minCostPrice ? Money::parse((string)$minCostPrice, $currencyObj) : null;
    }
}

