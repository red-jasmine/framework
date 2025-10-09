<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Organization\Domain\Repositories\MemberRepositoryInterface;
use RedJasmine\UserCore\Infrastructure\Repositories\BaseUserRepository;

class MemberRepository extends BaseUserRepository implements MemberRepositoryInterface
{
    protected static string $modelClass = Member::class;

}
