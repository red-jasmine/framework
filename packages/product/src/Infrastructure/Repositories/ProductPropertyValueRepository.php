<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品属性值仓库实现
 *
 * 基于Repository实现，提供商品属性值实体的读写操作能力
 */
class ProductPropertyValueRepository extends Repository implements ProductPropertyValueRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductPropertyValue::class;

    /**
     * 在指定属性中根据名称查找属性值
     */
    public function findByNameInProperty(int $pid, string $name)
    {
        return static::$modelClass::where('pid', $pid)->where('name', $name)->first();
    }

    /**
     * 在指定属性中根据ID数组查找属性值列表
     */
    public function findByIdsInProperty(int $pid, array $ids)
    {
        return $this->query()->where('pid', $pid)->whereIn('id', $ids)->get();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('pid'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('status'),
        ];
    }
}
