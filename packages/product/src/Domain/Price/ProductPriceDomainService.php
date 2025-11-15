<?php

namespace RedJasmine\Product\Domain\Price;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Money\Data\Money;
use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Product\Domain\Price\Data\ProductPriceData;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Product\Domain\Price\Repositories\ProductPriceRepositoryInterface;
use RedJasmine\Product\Domain\Price\Services\PriceMatcher;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;

class ProductPriceDomainService extends Service
{
    public function __construct(
        protected ProductRepositoryInterface $repository,
        protected ProductPriceRepositoryInterface $priceRepository,
        protected PriceMatcher $priceMatcher,
    ) {
    }

    /**
     * 获取商品价格信息（兼容旧接口）
     * - 价格
     * - 市场价格
     * - 税率
     *
     * @param ProductPurchaseFactor $data
     * @return ProductAmountInfo
     */
    public function getProductAmount(ProductPurchaseFactor $data): ProductAmountInfo
    {
        $product = $this->repository->find($data->product->id);
        $productAmount = new ProductAmountInfo(new Currency($product->currency->getCode()));

        $sku = $product->getSkuBySkuId($data->product->skuId);

        // 尝试从多维度价格表获取价格
        $priceData = new ProductPriceData();
        $priceData->productId = $data->product->id;
        $priceData->skuId = $data->product->skuId;
        $priceData->market = $product->market ?? '*';
        $priceData->store = '*';
        // TODO: 从用户对象获取用户等级，目前使用默认值
        $priceData->userLevel = 'default';
        $priceData->quantity = $data->quantity ?? 1;

        $matchedPrice = $this->getPrice($priceData);

        if ($matchedPrice) {
            $productAmount->price = $matchedPrice->price;
            $productAmount->marketPrice = $matchedPrice->market_price;
            $productAmount->setCostPrice($matchedPrice->cost_price ?? Money::parse(0));
        } else {
            // 回退到基准价格
            $productAmount->price = $sku->price;
            $productAmount->marketPrice = $sku->market_price;
            $productAmount->setCostPrice($sku->cost_price ?? Money::parse(0));
        }

        $productAmount->taxRate = $product->tax_rate ?? 0;

        return $productAmount;
    }

    /**
     * 获取商品变体价格（新接口）
     *
     * @param ProductPriceData $data
     * @return ProductVariantPrice|null
     */
    public function getPrice(ProductPriceData $data): ?ProductVariantPrice
    {
        // 1. 尝试从 product_variant_prices 表匹配价格
        $matchedPrice = $this->matchPrice(
            $data->productId,
            $data->skuId,
            $data->market,
            $data->store,
            $data->userLevel
        );

        if ($matchedPrice) {
            // 如果有阶梯价格，计算阶梯价格
            if ($matchedPrice->quantity_tiers && $data->quantity > 1) {
                $tierPrice = $this->calculateTierPrice($matchedPrice, $data->quantity);
                if ($tierPrice) {
                    // 创建临时价格对象返回阶梯价格
                    $tierPriceObj = clone $matchedPrice;
                    $tierPriceObj->price = $tierPrice;
                    return $tierPriceObj;
                }
            }
            return $matchedPrice;
        }

        // 2. 回退到 product_variants 表的基准价格（所有价格都挂在变体上）
        $product = $this->repository->find($data->productId);
        $variant = $product->getSkuBySkuId($data->skuId);
        if ($variant && $variant->price) {
            return $this->createPriceFromVariant($variant, $data);
        }

        return null;
    }

    /**
     * 匹配变体价格
     *
     * @param int $productId 商品ID
     * @param int $variantId SKU ID（必填）
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @return ProductVariantPrice|null
     */
    public function matchPrice(
        int $productId,
        int $variantId,
        string $market,
        string $store,
        string $userLevel
    ): ?ProductVariantPrice {
        // 查询所有可能匹配的价格
        $prices = ProductVariantPrice::query()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->byDimensions($market, $store, $userLevel)
            ->get();

        if ($prices->isEmpty()) {
            return null;
        }

        // 使用 PriceMatcher 匹配最佳价格
        return $this->priceMatcher->match($prices, $market, $store, $userLevel);
    }

    /**
     * 计算阶梯价格
     *
     * @param ProductVariantPrice $price 价格对象
     * @param int $quantity 数量
     * @return Money|null
     */
    public function calculateTierPrice(ProductVariantPrice $price, int $quantity): ?Money
    {
        if (!$price->quantity_tiers || empty($price->quantity_tiers)) {
            return null;
        }

        $tiers = $price->quantity_tiers;
        $matchedTier = null;

        foreach ($tiers as $tier) {
            $min = $tier['min'] ?? 1;
            $max = $tier['max'] ?? null;

            if ($quantity >= $min && ($max === null || $quantity <= $max)) {
                $matchedTier = $tier;
                break;
            }
        }

        if ($matchedTier && isset($matchedTier['price'])) {
            return Money::parse($matchedTier['price'], $price->currency);
        }

        return null;
    }

    /**
     * 从 Variant 创建价格对象（用于回退）
     *
     * @param \RedJasmine\Product\Domain\Product\Models\ProductVariant $variant
     * @param ProductPriceData $data
     * @return ProductVariantPrice
     */
    protected function createPriceFromVariant($variant, ProductPriceData $data): ProductVariantPrice
    {
        $price = new ProductVariantPrice();
        $price->product_id = $data->productId;
        $price->variant_id = $data->skuId;
        $price->market = $data->market;
        $price->store = $data->store;
        $price->user_level = $data->userLevel;
        $price->currency = $variant->currency->getCode();
        $price->price = $variant->price;
        $price->market_price = $variant->market_price;
        $price->cost_price = $variant->cost_price;
        $price->priority = 0;

        return $price;
    }

    /**
     * 批量获取商品级别价格汇总（用于商品列表）
     *
     * @param Collection<Product> $products 商品集合
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @return array<int, ProductPrice|null> key为 product_id，value为价格汇总对象或null
     */
    public function getBatchProductPrices(
        Collection $products,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*'
    ): array {
        if ($products->isEmpty()) {
            return [];
        }

        $productIds = $products->pluck('id')->toArray();

        // 批量查询商品级别价格汇总
        $prices = $this->priceRepository->findBatchPrices(
            $productIds,
            $market,
            $store,
            $userLevel
        );

        // 构建价格映射表
        $result = [];
        foreach ($products as $product) {
            $result[$product->id] = $prices->get($product->id);
        }

        return $result;
    }

    /**
     * 批量获取商品变体价格（用于商品详情）
     *
     * @param Collection<Product> $products 商品集合
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @param bool $useDefaultVariant 是否只查询默认变体（true：只查询默认变体，false：查询所有变体）
     * @return array<string, ProductVariantPrice|null> key为 "product_id-variant_id"，value为价格对象或null
     */
    public function getBatchPrices(
        Collection $products,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*',
        bool $useDefaultVariant = true
    ): array {
        if ($products->isEmpty()) {
            return [];
        }

        $productIds = $products->pluck('id')->toArray();
        $variantIds = [];
        $variantMap = []; // product_id => variant_id
        $variantDataMap = []; // variant_id => variant_data (用于回退价格)

        // 收集需要查询的变体ID
        if ($useDefaultVariant) {
            // 只查询默认变体：批量查询默认变体ID（避免预加载所有变体）
            $defaultVariants = \RedJasmine\Product\Domain\Product\Models\ProductVariant::query()
                ->whereIn('product_id', $productIds)
                ->where('attrs_sequence', \RedJasmine\Product\Domain\Product\Models\Product::$defaultAttrsSequence)
                ->get(['id', 'product_id', 'price', 'market_price', 'cost_price', 'currency']);

            foreach ($defaultVariants as $variant) {
                $variantIds[] = $variant->id;
                $variantMap[$variant->product_id] = $variant->id;
                $variantDataMap[$variant->id] = $variant;
            }
        } else {
            // 查询所有变体（需要预加载 variants）
            foreach ($products as $product) {
                foreach ($product->variants as $variant) {
                    $variantIds[] = $variant->id;
                    if (!isset($variantMap[$product->id])) {
                        $variantMap[$product->id] = [];
                    }
                    $variantMap[$product->id][] = $variant->id;
                    $variantDataMap[$variant->id] = $variant;
                }
            }
        }

        if (empty($variantIds)) {
            return [];
        }

        // 批量查询变体价格
        $pricesQuery = ProductVariantPrice::query()
            ->whereIn('product_id', $productIds)
            ->byDimensions($market, $store, $userLevel);

        if (!empty($variantIds)) {
            $pricesQuery->whereIn('variant_id', $variantIds);
        }

        $allPrices = $pricesQuery->get();

        // 使用 PriceMatcher 为每个 product_id-variant_id 组合匹配最佳价格
        $groupedPrices = $allPrices->groupBy(function ($price) {
            return "{$price->product_id}-{$price->variant_id}";
        });

        $prices = collect();
        foreach ($groupedPrices as $key => $group) {
            $bestPrice = $this->priceMatcher->match($group, $market, $store, $userLevel);
            if ($bestPrice) {
                $prices->put($key, $bestPrice);
            }
        }

        // 构建价格映射表
        $priceMap = [];
        foreach ($prices as $key => $price) {
            $priceMap[$key] = $price;
        }

        // 对于没有匹配到价格的变体，回退到变体的基准价格
        $result = [];
        foreach ($products as $product) {
            if ($useDefaultVariant) {
                $variantId = $variantMap[$product->id] ?? null;
                if ($variantId) {
                    $key = "{$product->id}-{$variantId}";
                    if (isset($priceMap[$key])) {
                        $result[$key] = $priceMap[$key];
                    } else {
                        // 回退到变体的基准价格（使用批量查询的变体数据）
                        $variant = $variantDataMap[$variantId] ?? null;
                        if ($variant && $variant->price) {
                            $priceData = new ProductPriceData();
                            $priceData->productId = $product->id;
                            $priceData->skuId = $variantId;
                            $priceData->market = $market;
                            $priceData->store = $store;
                            $priceData->userLevel = $userLevel;
                            $result[$key] = $this->createPriceFromVariant($variant, $priceData);
                        } else {
                            $result[$key] = null;
                        }
                    }
                }
            } else {
                // 查询所有变体
                $productVariantIds = $variantMap[$product->id] ?? [];
                foreach ($productVariantIds as $variantId) {
                    $key = "{$product->id}-{$variantId}";
                    if (isset($priceMap[$key])) {
                        $result[$key] = $priceMap[$key];
                    } else {
                        // 回退到变体的基准价格（使用预加载的变体数据）
                        $variant = $variantDataMap[$variantId] ?? null;
                        if ($variant && $variant->price) {
                            $priceData = new ProductPriceData();
                            $priceData->productId = $product->id;
                            $priceData->skuId = $variantId;
                            $priceData->market = $market;
                            $priceData->store = $store;
                            $priceData->userLevel = $userLevel;
                            $result[$key] = $this->createPriceFromVariant($variant, $priceData);
                        } else {
                            $result[$key] = null;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 获取商品级别价格汇总
     *
     * @param int $productId 商品ID
     * @param string $market 市场
     * @param string $store 门店
     * @param string $userLevel 用户等级
     * @return ProductPrice|null
     */
    public function getProductPrice(
        int $productId,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*'
    ): ?ProductPrice {
        return $this->priceRepository->findByDimensions($productId, $market, $store, $userLevel);
    }
}
