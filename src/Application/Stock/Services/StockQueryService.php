<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Domain\Stock\Repositories\ProductSkuReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class StockQueryService extends ApplicationQueryService
{
    public function __construct(
        protected ProductSkuReadRepositoryInterface $repository
    )
    {
        parent::__construct();
    }

    public function allowedSorts() : array
    {
        return [
            AllowedSort::field('stock'),
            AllowedSort::field('safety_stock'),
            AllowedSort::field('sales'),
        ];
    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('sku_id', 'id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('safety_stock'),
            AllowedFilter::exact('status'),
        ];
    }

    public function allowedIncludes() : array
    {
        return [
            'product'
        ];
    }

}
