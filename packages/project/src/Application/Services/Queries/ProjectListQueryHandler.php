<?php

namespace RedJasmine\Project\Application\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\Queries\QueryHandler;

class ProjectListQueryHandler extends QueryHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectListQuery $query): LengthAwarePaginator
    {
        return $this->service->paginate($query);
    }
}
