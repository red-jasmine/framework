<?php

namespace RedJasmine\Invitation\Infrastructure\Repositories\Eloquent;

use RedJasmine\Invitation\Domain\Models\InvitationRecord;
use RedJasmine\Invitation\Domain\Repositories\InvitationRecordRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * 邀请记录Eloquent仓库实现
 */
class InvitationRecordRepository extends EloquentRepository implements InvitationRecordRepositoryInterface
{
    protected static string $eloquentModelClass = InvitationRecord::class;

    /**
     * 根据邀请码和被邀请人查找记录
     */
    public function findByCodeAndInvitee(int $invitationCodeId, string $inviteeType, int $inviteeId): ?InvitationRecord
    {
        return InvitationRecord::where('invitation_code_id', $invitationCodeId)
            ->where('invitee_type', $inviteeType)
            ->where('invitee_id', $inviteeId)
            ->first();
    }

    /**
     * 检查是否已经使用过该邀请码
     */
    public function hasUsedCode(string $inviteeType, int $inviteeId, int $invitationCodeId): bool
    {
        return InvitationRecord::where('invitee_type', $inviteeType)
            ->where('invitee_id', $inviteeId)
            ->where('invitation_code_id', $invitationCodeId)
            ->exists();
    }
} 