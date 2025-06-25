<?php

namespace RedJasmine\Invitation\Domain\Repositories;

use RedJasmine\Invitation\Domain\Models\InvitationRecord;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 邀请记录仓库接口
 * 
 * @method InvitationRecord find($id)
 */
interface InvitationRecordRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据邀请码和被邀请人查找记录
     */
    public function findByCodeAndInvitee(int $invitationCodeId, string $inviteeType, int $inviteeId): ?InvitationRecord;

    /**
     * 检查是否已经使用过该邀请码
     */
    public function hasUsedCode(string $inviteeType, int $inviteeId, int $invitationCodeId): bool;
} 