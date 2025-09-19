<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\Organization;
use RedJasmine\Organization\Domain\Repositories\OrganizationRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class OrganizationRepository extends Repository implements OrganizationRepositoryInterface
{
    protected static string $modelClass = Organization::class;
}
