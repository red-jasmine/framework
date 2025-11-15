<?php

namespace RedJasmine\Product\Application\Product\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 用户商品列表查询处理器
 *
 * 根据价格维度信息查询商品列表，并自动附加价格信息
 */
class UserProductListQueryHandler extends QueryHandler
{
    public function __construct(
        protected ProductApplicationService $service,
        protected PriceApplicationService $priceService
    ) {
    }

    /**
     * 处理用户商品列表查询
     *
     * @param UserProductListQuery $query
     * @return LengthAwarePaginator<Product>
     */
    public function handle(UserProductListQuery $query): LengthAwarePaginator
    {
        // 如果提供了用户，尝试从用户获取用户等级
        if ($query->user && $query->userLevel === 'default') {
            // TODO: 从用户对象获取用户等级
            // $query->userLevel = $query->user->getUserLevel() ?? 'default';
        }

        // 商品列表查询不需要预加载变体（使用商品级价格汇总表）
        if (empty($query->include)) {
            $query->include = ['category', 'brand', 'tags'];
        } else {
            // 移除 variants（商品列表使用汇总表，不需要变体）
            $query->include = array_filter($query->include, fn($item) => $item !== 'variants');
        }

        // 查询商品列表
        $products = $this->service->repository->paginate($query);

        // 批量查询商品级别价格汇总（使用汇总表，性能最优）
        $productsCollection = collect($products->items());
        $prices = $this->priceService->getBatchProductPrices(
            $productsCollection,
            $query->market,
            $query->store,
            $query->userLevel
        );

        // 将价格汇总附加到商品
        foreach ($products->items() as $product) {
            $price = $prices[$product->id] ?? null;

            // 附加价格汇总到商品
            $product->setAttribute('current_price', $price);
            $product->setAttribute('current_price_obj', $price);

            // 如果有价格汇总，附加价格信息
            if ($price) {
                $product->setAttribute('price', $price->price);
                $product->setAttribute('market_price', $price->market_price);
                $product->setAttribute('cost_price', $price->cost_price);
            }
        }

        return $products;
    }
}

