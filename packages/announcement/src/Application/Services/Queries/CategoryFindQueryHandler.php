<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;

class CategoryFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function handle(CategoryFindQuery $query): ?AnnouncementCategory
    {
        return $this->repository->findByQuery($query);
    }
}
