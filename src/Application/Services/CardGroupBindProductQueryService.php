<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Infrastructure\ReadRepositories\Mysql\CardGroupBindProductReadRepository;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class CardGroupBindProductQueryService extends ApplicationQueryService
{


    public function __construct(
        protected CardGroupBindProductReadRepository $repository,
    )
    {
        parent::__construct();
    }

    public function allowedIncludes() : array
    {
        return  ['group'];
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
