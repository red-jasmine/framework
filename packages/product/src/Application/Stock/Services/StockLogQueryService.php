<?php

namespace RedJasmine\Product\Application\Stock\Services;

use RedJasmine\Product\Domain\Stock\Repositories\ProductStockLogRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use Spatie\QueryBuilder\AllowedFilter;

class StockLogQueryService extends ApplicationService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.stock-log.query';

    public function __construct(
        protected ProductStockLogRepositoryInterface $repository
    ) {

    }

    protected static $macros = [
        'create' => null,
        'update' => null,
        'delete' => null
    ];


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
