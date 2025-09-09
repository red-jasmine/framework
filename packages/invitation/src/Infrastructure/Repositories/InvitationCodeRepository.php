<?php

namespace RedJasmine\Invitation\Infrastructure\Repositories;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 邀请码仓库实现
 *
 * 基于Repository实现，提供邀请码实体的读写操作能力
 */
class InvitationCodeRepository extends Repository implements InvitationCodeRepositoryInterface
{
    protected static string $modelClass = InvitationCode::class;

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode
    {
        return InvitationCode::where('code', $code)->first();
    }

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool
    {
        return InvitationCode::where('code', $code)->exists();
    }

    /**
     * 获取用户的邀请码统计
     */
    public function getUserInvitationStatistics($userId, $userType): array
    {
        // 邀请码总数
        $totalCodes = InvitationCode::where('inviter_type', $userType)
            ->where('inviter_id', $userId)
            ->count();

        // 有效邀请码数
        $activeCodes = InvitationCode::where('inviter_type', $userType)
            ->where('inviter_id', $userId)
            ->available()
            ->count();

        // 总使用次数
        $totalUsed = InvitationCode::where('inviter_type', $userType)
            ->where('inviter_id', $userId)
            ->sum('used_count');

        // 今日使用次数
        $todayUsed = InvitationCode::where('inviter_type', $userType)
            ->where('inviter_id', $userId)
            ->whereHas('records', function ($query) {
                $query->whereDate('invited_at', today());
            })
            ->count();

        return [
            'total_codes' => $totalCodes,
            'active_codes' => $activeCodes,
            'total_used' => $totalUsed,
            'today_used' => $todayUsed,
        ];
    }

    /**
     * 获取邀请码使用排行
     */
    public function getUsageRanking(int $limit = 10): array
    {
        return InvitationCode::select([
                'id',
                'code',
                'inviter_type',
                'inviter_id',
                'inviter_nickname',
                'used_count',
                'max_usage'
            ])
            ->orderByDesc('used_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 获取邀请统计
     */
    public function getInvitationStats(int $inviterId, string $inviterType): array
    {
        return [
            'total_codes' => InvitationCode::where('owner_type', $inviterType)->where('owner_id', $inviterId)->count(),
            'active_codes' => InvitationCode::where('owner_type', $inviterType)->where('owner_id', $inviterId)->where('status', 'active')->count(),
            'total_usage' => InvitationCode::where('owner_type', $inviterType)->where('owner_id', $inviterId)->sum('used_count'),
            'expired_codes' => InvitationCode::where('owner_type', $inviterType)->where('owner_id', $inviterId)->where('expired_at', '<', now())->count(),
            'exhausted_codes' => InvitationCode::where('owner_type', $inviterType)->where('owner_id', $inviterId)->whereColumn('used_count', '>=', 'max_usage')->where('max_usage', '>', 0)->count(),
        ];
    }

    /**
     * 获取热门邀请码
     */
    public function getPopularCodes(int $limit = 10): array
    {
        return InvitationCode::where('status', 'active')
            ->orderBy('used_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('code'),
            AllowedSort::field('used_count'),
            AllowedSort::field('max_usage'),
            AllowedSort::field('expired_at'),
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
            AllowedFilter::exact('code'),
            AllowedFilter::exact('code_type'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::scope('expired'),
            AllowedFilter::scope('available'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'invitationRecords',
        ];
    }
}
