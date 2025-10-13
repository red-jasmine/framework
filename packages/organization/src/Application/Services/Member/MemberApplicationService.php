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

    protected static $macros = [
        'hire' => \RedJasmine\Organization\Application\Services\Member\Commands\MemberHireCommandHandler::class,
        'resign' => \RedJasmine\Organization\Application\Services\Member\Commands\MemberResignCommandHandler::class,
    ];

    public function __construct(
        public MemberRepositoryInterface $repository,
        public MemberTransformer $transformer
    ) {
    }

    public function getGuard() : string
    {
        return 'member';
    }

    /**
     * 成员入职
     */
    public function hire(int $memberId): Member
    {
        $member = $this->repository->find($memberId);
        $member->hire();
        return $member;
    }

    /**
     * 成员离职
     */
    public function resign(int $memberId): Member
    {
        $member = $this->repository->find($memberId);
        $member->resign();
        return $member;
    }

    /**
     * 检查成员是否活跃
     */
    public function isActive(int $memberId): bool
    {
        $member = $this->repository->find($memberId);
        return $member->isActive();
    }

    /**
     * 根据账号名称和组织ID查找成员
     */
    public function findByNameAndOrgId(string $name, int $orgId): ?Member
    {
        return $this->repository->findByNameAndOrgId($name, $orgId);
    }
}


