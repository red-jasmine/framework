<?php

namespace RedJasmine\Product\Application\Property\Services;

use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;


class ProductPropertyQueryService extends ApplicationQueryService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product-property.query';

    public function __construct(
        protected ProductPropertyReadRepositoryInterface $repository
    ) {
        parent::__construct();
    }

    public function allowedIncludes() : array
    {
        return [
            'group'
        ];
    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('group_id'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
        ];
    }


}
