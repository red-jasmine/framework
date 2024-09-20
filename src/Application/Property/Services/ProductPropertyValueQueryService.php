<?php

namespace RedJasmine\Product\Application\Property\Services;

use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class ProductPropertyValueQueryService extends ApplicationQueryService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product-property-value.query';


    public function __construct(
        protected ProductPropertyValueReadRepositoryInterface $repository
    )
    {

    }


    public function allowedIncludes() : array
    {
        return [
            'group',
            'property',
        ];
    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('pid'),
            AllowedFilter::exact('group_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::partial('name'),
        ];
    }


}
