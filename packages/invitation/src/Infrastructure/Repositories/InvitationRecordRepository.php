<?php

namespace RedJasmine\Invitation\Infrastructure\Repositories;

use RedJasmine\Invitation\Domain\Models\InvitationRecord;
use RedJasmine\Invitation\Domain\Repositories\InvitationRecordRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 邀请记录仓库实现
 *
 * 基于Repository实现，提供邀请记录实体的读写操作能力
 */
class InvitationRecordRepository extends Repository implements InvitationRecordRepositoryInterface
{
    protected static string $modelClass = InvitationRecord::class;

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

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('invited_at'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('invitation_code_id'),
            AllowedFilter::exact('invitee_type'),
            AllowedFilter::exact('invitee_id'),
            AllowedFilter::exact('inviter_type'),
            AllowedFilter::exact('inviter_id'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'invitationCode',
        ];
    }
}
