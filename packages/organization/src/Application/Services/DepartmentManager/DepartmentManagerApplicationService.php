<?php

namespace RedJasmine\Organization\Application\Services\DepartmentManager;

use RedJasmine\Organization\Domain\Models\DepartmentManager;
use RedJasmine\Organization\Domain\Repositories\DepartmentManagerRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\DepartmentManagerTransformer;
use RedJasmine\Support\Application\ApplicationService;

class DepartmentManagerApplicationService extends ApplicationService
{
    public function __construct(
        public DepartmentManagerRepositoryInterface $repository,
        public DepartmentManagerTransformer $transformer
    ) {
    }

    protected static string $modelClass = DepartmentManager::class;
}


