<?php

namespace RedJasmine\Product\Application\Series\Services\Queries;

use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindProductSeriesQueryHandler extends QueryHandler
{


    public function __construct(
        protected ProductSeriesApplicationService $service
    ) {
    }

    public function handle(FindProductSeriesQuery $query)
    {
        //$this->service->repository

        return $this->service->repository->findProductSeries($query->getKey());
    }

}