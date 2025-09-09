<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品服务仓库实现
 *
 * 基于Repository实现，提供商品服务实体的读写操作能力
 */
class ProductServiceRepository extends Repository implements ProductServiceRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductService::class;

    /**
     * 根据名称查找服务
     */
    public function findByName($name): ?ProductService
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
