<?php

namespace RedJasmine\Organization\Application\Services\Member;

use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Organization\Domain\Repositories\MemberRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\MemberTransformer;
use RedJasmine\User\Application\Services\BaseUserApplicationService;

class MemberApplicationService extends BaseUserApplicationService
{

    public static string    $hookNamePrefix = 'organization.application.member';

    protected static string $modelClass     = Member::class;

    public function __construct(
        public MemberRepositoryInterface $repository,
        public MemberTransformer $transformer
    ) {
    }

    public function getGuard() : string
    {
        return 'member';
    }
}


