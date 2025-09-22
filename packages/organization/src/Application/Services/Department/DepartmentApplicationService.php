<?php

namespace RedJasmine\Organization\Application\Services\Department;

use RedJasmine\Organization\Domain\Models\Department;
use RedJasmine\Organization\Domain\Repositories\DepartmentRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\DepartmentTransformer;
use RedJasmine\Support\Application\ApplicationService;

class DepartmentApplicationService extends ApplicationService
{
    public function __construct(
        public DepartmentRepositoryInterface $repository,
        public DepartmentTransformer $transformer
    ) {
    }

    protected static string $modelClass = Department::class;
}


