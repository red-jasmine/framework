<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 积分商品分类仓库实现
 *
 * 基于Repository实现，提供积分商品分类实体的读写操作能力
 */
class PointProductCategoryRepository extends Repository implements PointProductCategoryRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = PointsProductCategory::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
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
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
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
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'parent',
            'children',
            'products',
        ];
    }

    /**
     * 根据名称查找分类
     */
    public function findByName($name): ?PointsProductCategory
    {
        return $this->query()
            ->where('name', $name)
            ->first();
    }

    /**
     * 获取树形结构分类
     */
    public function tree(Query $query): array
    {
        return $this->buildTree($query);
    }

    /**
     * 查找用户的分类
     */
    public function findByOwner(string $ownerType, string $ownerId): Collection
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
    public function findEnabled(): Collection
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
    public function findShow(): Collection
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
    public function findLeaf(): Collection
    {
        return $this->query()
            ->leaf()
            ->orderBy('sort', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
