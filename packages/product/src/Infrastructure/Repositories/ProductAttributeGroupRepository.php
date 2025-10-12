<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeGroup;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品属性组仓库实现
 *
 * 基于Repository实现，提供商品属性组实体的读写操作能力
 */
class ProductAttributeGroupRepository extends Repository implements ProductAttributeGroupRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductAttributeGroup::class;

    /**
     * 根据名称查找属性组
     */
    public function findByName(string $name)
    {
        return static::$modelClass::where('name', $name)->first();
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
            AllowedFilter::exact('status'),
        ];
    }
}
