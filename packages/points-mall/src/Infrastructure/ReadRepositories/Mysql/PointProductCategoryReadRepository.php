<?php

namespace RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class PointProductCategoryReadRepository extends QueryBuilderReadRepository implements PointProductCategoryReadRepositoryInterface
{
    use HasTree;

    public static $modelClass = PointsProductCategory::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
        ];
    }

    /**
     * 允许的排序字段配置
     */
    public function allowedSorts(): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('name'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('sort'),
        ];
    }

    /**
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            'parent',
            'children',
            'products',
        ];
    }

    /**
     * 查找用户的分类
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
     * 查找启用的分类
     */
    public function findEnabled(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->enable()
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 查找显示的分类
     */
    public function findShow(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->show()
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 查找叶子分类
     */
    public function findLeaf(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->leaf()
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
} 