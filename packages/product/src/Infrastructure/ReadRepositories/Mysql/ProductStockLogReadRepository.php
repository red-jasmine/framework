<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ProductStockLogReadRepository extends QueryBuilderReadRepository implements ProductStockLogReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ProductStockLog::class;


    public function allowedIncludes() : array
    {
        return [
            'product',
            'sku',
        ];
    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('sku_id'),
            AllowedFilter::exact('type'),
        ];
    }


}
