<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class CardQueryService extends ApplicationQueryService
{


    public function __construct(
        protected CardReadRepositoryInterface $repository,
    )
    {
        parent::__construct();
    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('sku_id'),
            AllowedFilter::exact('is_loop'),
            AllowedFilter::exact('status'),
        ];
    }

}
