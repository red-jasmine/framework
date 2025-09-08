<?php

namespace RedJasmine\Announcement\Application\Services\Queries;

use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Support\Application\Queries\QueryHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnnouncementListQueryHandler extends QueryHandler
{
    public function __construct(
        protected AnnouncementRepositoryInterface $repository
    ) {
    }

    public function handle(AnnouncementListQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate($query);
    }
}
