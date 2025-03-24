<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PaginateQueryHandler extends QueryHandler
{

    public function __construct(
        protected $service
    ) {
    }


    public function handle(PaginateQuery $query) : LengthAwarePaginator
    {
        return $this->service->readRepository->paginate($query);
    }

}
