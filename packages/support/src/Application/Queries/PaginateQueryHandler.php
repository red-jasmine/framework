<?php

namespace RedJasmine\Support\Application\Queries;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PaginateQueryHandler extends QueryHandler
{

    public function __construct(
        protected $service
    ) {
    }


    public function handle(PaginateQuery $query) : LengthAwarePaginator|Paginator
    {
        return $this->service->readRepository->paginate($query);
    }

}
