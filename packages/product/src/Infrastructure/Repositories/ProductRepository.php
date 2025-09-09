<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 商品仓库实现
 *
 * 基于Repository实现，提供商品实体的读写操作能力
 */
class ProductRepository extends Repository implements ProductRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Product::class;

    /**
     * 根据ID数组查找商品列表
     */
    public function findList(array $ids)
    {
        return $this->query()->whereIn('id', $ids)->get();
    }

    /**
     * 根据SKU ID查找商品
     */
    public function findSkuById(int $skuId)
    {
        return $this->query()->findOrFail($skuId);
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('market'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('shipping_type'),
            AllowedFilter::exact('outer_id'),
            AllowedFilter::exact('is_multiple_spec'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('brand_id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('product_group_id'),
            AllowedFilter::exact('barcode'),
            AllowedFilter::exact('product_model'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('price'),
            AllowedSort::field('cost_price'),
            AllowedSort::field('market_price'),
            AllowedSort::field('sales'),
            AllowedSort::field('stock'),
            AllowedSort::field('on_sale_time'),
            AllowedSort::field('modified_time'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'extension',
            'services',
            'skus',
            'brand',
            'category',
            'productGroups',
            'extendProductGroups',
            'series',
            'tags',
        ];
    }
}
