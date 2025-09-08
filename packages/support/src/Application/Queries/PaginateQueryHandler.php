<?php

namespace RedJasmine\Support\Application\Queries;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\BaseRepositoryInterface;

/**
 * @property ApplicationService $service
 */
class PaginateQueryHandler extends QueryHandler
{

    public function __construct(
        protected $service
    ) {
    }


    public function handle(PaginateQuery $query) : LengthAwarePaginator|Paginator
    {
        if ($this->service->repository instanceof BaseRepositoryInterface) {
            return $this->service->repository->paginate($query);
        }

        return $this->service->readRepository->paginate($query);
    }

}
