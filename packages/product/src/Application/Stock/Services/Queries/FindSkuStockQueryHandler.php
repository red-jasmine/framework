<?php

namespace RedJasmine\Product\Application\Stock\Services\Queries;

use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindSkuStockQueryHandler extends QueryHandler
{

    public function __construct(
        protected StockApplicationService $service
    ) {
    }

    public function handle(FindSkuStockQuery $query)
    {
        return $this->service->repository->find($query);
    }

}