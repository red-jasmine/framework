<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;

class AnnouncementFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected AnnouncementRepositoryInterface $repository
    ) {
    }

    public function handle(AnnouncementFindQuery $query): ?Announcement
    {
        return $this->repository->findByQuery($query);
    }
}
