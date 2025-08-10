<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementReadRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;

class AnnouncementFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected AnnouncementReadRepositoryInterface $readRepository
    ) {
    }

    public function handle(AnnouncementFindQuery $query): ?Announcement
    {
        return $this->readRepository->find($query);
    }
}
