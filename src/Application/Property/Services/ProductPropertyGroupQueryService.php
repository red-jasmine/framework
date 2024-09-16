<?php

namespace RedJasmine\Product\Application\Property\Services;

use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class ProductPropertyGroupQueryService extends ApplicationQueryService
{

    public static string $hookNamePrefix = 'product.application.product-property-group.query';

    public function __construct(
        protected ProductPropertyGroupReadRepositoryInterface $repository

    )
    {

        parent::__construct();
    }

    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
        ];
    }


}
