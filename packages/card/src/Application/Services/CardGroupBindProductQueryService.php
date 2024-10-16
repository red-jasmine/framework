<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Repositories\CardGroupBindProductReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class CardGroupBindProductQueryService extends ApplicationQueryService
{


    public function __construct(
        protected CardGroupBindProductReadRepositoryInterface $repository,
    ) {

    }

    public function allowedIncludes() : array
    {
        return ['group','product'];
    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('sku_id'),
            AllowedFilter::exact('group_id'),

        ];
    }

}
