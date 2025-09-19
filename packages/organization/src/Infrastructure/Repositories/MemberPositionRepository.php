<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\MemberPosition;
use RedJasmine\Organization\Domain\Repositories\MemberPositionRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class MemberPositionRepository extends Repository implements MemberPositionRepositoryInterface
{
    protected static string $modelClass = MemberPosition::class;

    /**
     * 查找成员的职位历史
     */
    public function findByMemberId(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return MemberPosition::where('member_id', $memberId)->get();
    }

    /**
     * 查找职位的成员历史
     */
    public function findByPositionId(int $positionId): \Illuminate\Database\Eloquent\Collection
    {
        return MemberPosition::where('position_id', $positionId)->get();
    }

    /**
     * 查找成员当前有效的职位关系
     */
    public function findActiveMemberPositions(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return MemberPosition::where('member_id', $memberId)
            ->whereNull('ended_at')
            ->get();
    }
}
