<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeValueRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品属性值仓库实现
 *
 * 基于Repository实现，提供商品属性值实体的读写操作能力
 */
class ProductAttributeValueRepository extends Repository implements ProductAttributeValueRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductAttributeValue::class;

    /**
     * 在指定属性中根据名称查找属性值
     */
    public function findByNameInAttribute(int $aid, string $name)
    {
        return static::$modelClass::where('aid', $aid)->where('name', $name)->first();
    }

    /**
     * 在指定属性中根据ID数组查找属性值列表
     */
    public function findByIdsInAttribute(int $aid, array $ids)
    {
        return $this->query()->where('aid', $aid)->whereIn('id', $ids)->get();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('aid'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('status'),
        ];
    }
}
