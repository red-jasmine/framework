<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品分类仓库实现
 *
 * 基于Repository实现，提供商品分类实体的读写操作能力
 */
class ProductCategoryRepository extends Repository implements ProductCategoryRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductCategory::class;

    /**
     * 根据名称查找分类
     */
    public function findByName($name): ?ProductCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }

    /**
     * 获取树形结构
     */
    public function tree(Query $query): array
    {
        return $this->getTree($query);
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('group_name'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('status'),
        ];
    }

    /**
     * 配置允许的字段
     */
    protected function allowedFields($query = null): array
    {
        return [
            'id',
            'parent_id',
            'name',
            'image',
            'group_name',
            'sort',
            'is_leaf',
            'is_show',
            'status',
            'extra',
        ];
    }
}
