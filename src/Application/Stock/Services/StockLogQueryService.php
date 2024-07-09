<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class StockLogQueryService extends ApplicationQueryService
{
    public function __construct(
        protected ProductStockLogReadRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


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
