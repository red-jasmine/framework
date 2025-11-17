<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Domain\Price\Models\ProductPrice;
use RedJasmine\Product\Domain\Price\Repositories\ProductPriceRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 商品级别价格汇总表仓库实现
 *
 * 基于Repository实现，提供商品级别价格汇总实体的读写操作能力
 */
class ProductPriceRepository extends Repository implements ProductPriceRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductPrice::class;

    /**
     * 根据维度查找商品级别价格汇总
     *
     * @param  int  $productId
     * @param  string  $market
     * @param  string  $store
     * @param  string  $userLevel
     * @param  int  $quantity
     *
     * @return ProductPrice|null
     */
    public function findByDimensions(
        int $productId,
        string $market,
        string $store,
        string $userLevel,
        int $quantity
    ) : ?ProductPrice {
        return $this->query()
                    ->where('product_id', $productId)
                    ->byDimensions($market, $store, $userLevel, $quantity)
                    ->first();
    }

    /**
     * 批量查询商品级别价格汇总
     */
    public function findBatchPrices(
        array $productIds,
        string $market = '*',
        string $store = '*',
        string $userLevel = '*'
    ) : Collection {
        if (empty($productIds)) {
            return collect();
        }

        return $this->query()
                    ->whereIn('product_id', $productIds)
                    ->byDimensions($market, $store, $userLevel)
                    ->get()
                    ->keyBy('product_id');
    }

    /**
     * 根据商品ID查找所有价格汇总
     */
    public function findByProduct(int $productId) : Collection
    {
        return $this->query()
                    ->where('product_id', $productId)
                    ->get();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('market'),
            AllowedFilter::exact('store'),
            AllowedFilter::exact('user_level'),
            AllowedFilter::exact('currency'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
    {
        return [
            AllowedSort::field('price'),
            AllowedSort::field('market_price'),
            AllowedSort::field('cost_price'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : array
    {
        return [
            'product',
        ];
    }
}
