<?php

namespace RedJasmine\Organization\Application\Services\Member;

use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Organization\Domain\Repositories\MemberRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\MemberTransformer;
use RedJasmine\Support\Application\ApplicationService;

class MemberApplicationService extends ApplicationService
{
    public function __construct(
        public MemberRepositoryInterface $repository,
        public MemberTransformer $transformer
    ) {
    }

    protected static string $modelClass = Member::class;
}


