<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryListQueryHandler extends QueryHandler
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function handle(CategoryListQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate($query);
    }
}
