<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Data\UserData;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品分组仓库实现
 *
 * 基于Repository实现，提供商品分组实体的读写操作能力
 */
class ProductGroupRepository extends Repository implements ProductGroupRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductGroup::class;

    /**
     * 获取树形结构
     */
    public function tree(Query $query): array
    {
        return $this->getTree($query);
    }

    /**
     * 根据名称查找分组
     */
    public function findByName($name): ?ProductGroup
    {
        return $this->query()->where('name', $name)->first();
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

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('group_name'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::callback('owner', fn(Builder $builder, $value) => $builder->onlyOwner(
                is_array($value) ? UserData::from($value) : $value
            )),
        ];
    }
}
