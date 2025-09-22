<?php

namespace RedJasmine\Project\Application\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\Queries\QueryHandler;

class ProjectRoleListQueryHandler extends QueryHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectRoleListQuery $query): LengthAwarePaginator
    {
        return $this->service->roleRepository->paginate($query);
    }
}
