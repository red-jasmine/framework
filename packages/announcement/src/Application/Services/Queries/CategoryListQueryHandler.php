<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Repositories\CategoryReadRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryListQueryHandler extends QueryHandler
{
    public function __construct(
        protected CategoryReadRepositoryInterface $readRepository
    ) {
    }

    public function handle(CategoryListQuery $query): LengthAwarePaginator
    {
        return $this->readRepository->paginate($query);
    }
}
