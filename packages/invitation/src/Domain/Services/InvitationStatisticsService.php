<?php

namespace RedJasmine\Invitation\Domain\Services;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\InvitationStatistics;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 邀请统计服务
 */
class InvitationStatisticsService extends Service
{
    public function __construct(
        protected InvitationCodeRepositoryInterface $repository
    ) {
    }

    /**
     * 更新邀请码统计
     */
    public function updateCodeStatistics(InvitationCode $invitationCode): void
    {
        $statistics = InvitationStatistics::firstOrNew([
            'invitation_code_id' => $invitationCode->id,
            'date' => now()->toDateString(),
        ]);

        // 基础统计
        $statistics->total_usage_count = $invitationCode->used_count;
        $statistics->current_status = $invitationCode->status;
        
        // 计算使用率
        if ($invitationCode->max_usage > 0) {
            $statistics->usage_rate = round(($invitationCode->used_count / $invitationCode->max_usage) * 100, 2);
        } else {
            $statistics->usage_rate = 0;
        }

        // 计算剩余使用次数
        if ($invitationCode->max_usage > 0) {
            $statistics->remaining_usage = max(0, $invitationCode->max_usage - $invitationCode->used_count);
        } else {
            $statistics->remaining_usage = null; // 无限制
        }

        // 过期状态
        $statistics->is_expired = $invitationCode->isExpired();
        
        // 更新时间
        $statistics->updated_at = now();

        $statistics->save();
    }

    /**
     * 生成邀请码使用报告
     */
    public function generateUsageReport(InvitationCode $invitationCode): array
    {
        $usageLogs = $invitationCode->usageLogs()
            ->orderBy('used_at', 'desc')
            ->get();

        // 按日期分组统计
        $dailyStats = $usageLogs->groupBy(function ($log) {
            return $log->used_at->toDateString();
        })->map(function ($logs) {
            return [
                'date' => $logs->first()->used_at->toDateString(),
                'count' => $logs->count(),
                'users' => $logs->unique(function ($log) {
                    return $log->user_type . ':' . $log->user_id;
                })->count(),
            ];
        })->values()->toArray();

        // 按用户类型分组统计
        $userTypeStats = $usageLogs->groupBy('user_type')->map(function ($logs, $userType) {
            return [
                'user_type' => $userType,
                'count' => $logs->count(),
                'unique_users' => $logs->unique('user_id')->count(),
            ];
        })->values()->toArray();

        return [
            'invitation_code_id' => $invitationCode->id,
            'code' => $invitationCode->code,
            'total_usage' => $invitationCode->used_count,
            'max_usage' => $invitationCode->max_usage,
            'usage_rate' => $invitationCode->usage_rate,
            'remaining_usage' => $invitationCode->remaining_usage,
            'daily_stats' => $dailyStats,
            'user_type_stats' => $userTypeStats,
            'generated_at' => now(),
        ];
    }

    /**
     * 获取热门邀请码
     */
    public function getPopularCodes(int $limit = 10): array
    {
        return $this->repository->getPopular($limit);
    }

    /**
     * 获取即将过期的邀请码
     */
    public function getExpiringSoon(int $hours = 24): array
    {
        return $this->repository->getExpiringSoon($hours);
    }

    /**
     * 清理过期统计数据
     */
    public function cleanupExpiredStatistics(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return InvitationStatistics::where('created_at', '<', $cutoffDate)->delete();
    }
} 