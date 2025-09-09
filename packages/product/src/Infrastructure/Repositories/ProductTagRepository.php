<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品标签仓库实现
 *
 * 基于Repository实现，提供商品标签实体的读写操作能力
 */
class ProductTagRepository extends Repository implements ProductTagRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductTag::class;

    /**
     * 根据名称查找标签
     */
    public function findByName($name): ?ProductTag
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
            AllowedFilter::exact('cluster'),
        ];
    }
}
