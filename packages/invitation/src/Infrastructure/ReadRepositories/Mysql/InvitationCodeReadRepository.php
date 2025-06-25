<?php

namespace RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 邀请码只读仓库MySQL实现
 */
class InvitationCodeReadRepository extends QueryBuilderReadRepository implements InvitationCodeReadRepositoryInterface
{
    public static $modelClass = InvitationCode::class;

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code)
    {
        return $this->query()->where('code', $code)->first();
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
     * 允许的排序字段配置
     */
    public function allowedSorts(): array
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
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
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
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            'invitationRecords',
        ];
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
} 