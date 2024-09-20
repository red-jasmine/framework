<?php

namespace RedJasmine\Product\Application\Series\Services;

use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class ProductSeriesQueryService extends ApplicationQueryService
{


    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix  = 'product.application.series.query';

    public function __construct(
        protected ProductSeriesReadRepositoryInterface $repository
    )
    {

    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),

        ];
    }

    public function allowedIncludes() : array
    {
        return [ 'products' ];
    }
}
