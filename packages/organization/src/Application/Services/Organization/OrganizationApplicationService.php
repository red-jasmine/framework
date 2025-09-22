<?php

namespace RedJasmine\Organization\Application\Services\Organization;

use RedJasmine\Organization\Domain\Models\Organization;
use RedJasmine\Organization\Domain\Repositories\OrganizationRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\OrganizationTransformer;
use RedJasmine\Support\Application\ApplicationService;

class OrganizationApplicationService extends ApplicationService
{
    public function __construct(
        public OrganizationRepositoryInterface $repository,
        public OrganizationTransformer $transformer
    ) {
    }

    protected static string $modelClass = Organization::class;
}


