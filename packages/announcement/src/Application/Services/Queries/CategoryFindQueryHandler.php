<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryReadRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;

class CategoryFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected CategoryReadRepositoryInterface $readRepository
    ) {
    }

    public function handle(CategoryFindQuery $query): ?AnnouncementCategory
    {
        return $this->readRepository->find($query);
    }
}
