<?php

namespace RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql;

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
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('product_id'),
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
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('sort'),
            AllowedSort::field('point'),
            AllowedSort::field('price_amount'),
        ];
    }

    /**
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            'category',
            'productSource',
        ];
    }

    /**
     * 查找上架商品
     */
    public function findOnSale(): \Illuminate\Database\Eloquent\Collection
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
    public function findByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection
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

    /**
     * 查找用户的商品
     */
    public function findByOwner(string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 查找商品及其分类
     */
    public function findWithCategory(string $productId): ?PointsProduct
    {
        return $this->query()
            ->with('category')
            ->where('id', $productId)
            ->first();
    }

    /**
     * 按分类查找上架商品
     */
    public function findOnSaleByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where('category_id', $categoryId)
            ->where('status', 'on_sale')
            ->where('stock', '>', 0)
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
} 