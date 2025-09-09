<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 商品库存日志仓库实现
 *
 * 基于Repository实现，提供商品库存日志实体的读写操作能力
 */
class ProductStockLogRepository extends Repository implements ProductStockLogRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductStockLog::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('sku_id'),
            AllowedFilter::exact('type'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'product',
            'sku',
        ];
    }
}
