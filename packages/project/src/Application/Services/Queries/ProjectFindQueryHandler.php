<?php

namespace RedJasmine\Project\Application\Services\Queries;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Application\Queries\QueryHandler;

class ProjectFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectFindQuery $query): ?Project
    {
        return $this->service->findByQuery($query);
    }
}
