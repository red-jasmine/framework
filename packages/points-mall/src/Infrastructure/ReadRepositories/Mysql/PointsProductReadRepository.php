<?php

namespace RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class PointsProductReadRepository extends QueryBuilderReadRepository implements PointsProductReadRepositoryInterface
{
    public static $modelClass = PointsProduct::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('payment_mode'),
            AllowedFilter::scope('min_point'),
            AllowedFilter::scope('max_point'),
            AllowedFilter::scope('min_price'),
            AllowedFilter::scope('max_price'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('search'),
        ];
    }

    /**
     * 允许的排序字段配置
     */
    public function allowedSorts(): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('title'),
            AllowedSort::field('point'),
            AllowedSort::field('price_amount'),
            AllowedSort::field('stock'),
            AllowedSort::field('sort'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            'category',
            'exchangeOrders',
        ];
    }

    /**
     * 查找上架商品
     */
    public function findOnSale(): Collection
    {
        return $this->query()
            ->where('status', 'on_sale')
            ->where('stock', '>', 0)
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 根据分类查找商品
     */
    public function findByCategory(int $categoryId): Collection
    {
        return $this->query()
            ->where('category_id', $categoryId)
            ->where('status', 'on_sale')
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 根据商品源查找商品
     */
    public function findByProductSource(string $productType, string $productId): ?PointsProduct
    {
        return $this->query()
            ->where('product_type', $productType)
            ->where('product_id', $productId)
            ->first();
    }
} 