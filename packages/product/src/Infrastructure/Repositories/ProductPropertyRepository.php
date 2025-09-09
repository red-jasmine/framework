<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品属性仓库实现
 *
 * 基于Repository实现，提供商品属性实体的读写操作能力
 */
class ProductPropertyRepository extends Repository implements ProductPropertyRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductProperty::class;

    /**
     * 根据名称查找属性
     */
    public function findByName(string $name)
    {
        return static::$modelClass::where('name', $name)->first();
    }

    /**
     * 根据ID数组查找属性列表
     */
    public function findByIds(array $ids)
    {
        return $this->query()->whereIn('id', $ids)->get();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('group_id'),
            AllowedFilter::exact('status'),
        ];
    }
}
