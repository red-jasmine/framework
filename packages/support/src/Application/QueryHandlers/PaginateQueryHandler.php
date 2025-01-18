<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PaginateQueryHandler extends QueryHandler
{

    public function __construct(
        protected ApplicationQueryService $service
    ) {
    }


    public function handle(PaginateQuery $query) : LengthAwarePaginator
    {

        return $this->service->getRepository()->paginate($query);


    }

}
