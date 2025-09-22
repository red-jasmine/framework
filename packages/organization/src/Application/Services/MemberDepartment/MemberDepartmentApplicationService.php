<?php

namespace RedJasmine\Organization\Application\Services\MemberDepartment;

use RedJasmine\Organization\Domain\Models\MemberDepartment;
use RedJasmine\Organization\Domain\Repositories\MemberDepartmentRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\MemberDepartmentTransformer;
use RedJasmine\Support\Application\ApplicationService;

class MemberDepartmentApplicationService extends ApplicationService
{
    public function __construct(
        public MemberDepartmentRepositoryInterface $repository,
        public MemberDepartmentTransformer $transformer
    ) {
    }

    protected static string $modelClass = MemberDepartment::class;
}


