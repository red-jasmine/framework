<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Support\Facades\DB;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Invitation\Domain\ReadRepositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Invitation\Domain\Models\Enums\CodeStatus;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

/**
 * 邀请码只读仓库MySQL实现
 */
final class InvitationCodeReadRepository extends QueryBuilderReadRepository implements InvitationCodeReadRepositoryInterface
{
    protected static string $modelClass = InvitationCode::class;
    /**
     * 根据ID查找邀请码
     */
    public function findById(int $id): ?InvitationCode
    {
        return InvitationCode::find($id);
    }

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode
    {
        return InvitationCode::where('code', $code)->first();
    }

    /**
     * 根据邀请人查找邀请码列表
     */
    public function findByInviter(Inviter $inviter): array
    {
        return InvitationCode::where('inviter_type', $inviter->type)
            ->where('inviter_id', $inviter->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool
    {
        return InvitationCode::where('code', $code)->exists();
    }

    /**
     * 分页查询邀请码（自定义方法，不与基类冲突）
     */
    public function paginateInvitationCodes(array $filters = [], int $page = 1, int $pageSize = 20): array
    {
        $query = InvitationCode::query()
            ->select([
                'id', 'code', 'inviter_type', 'inviter_id', 'inviter_name',
                'title', 'status', 'generate_type', 'max_usage', 'used_count',
                'expires_at', 'created_at', 'updated_at'
            ]);

        // 应用过滤条件
        $this->applyFilters($query, $filters);

        // 排序
        $query->orderBy('created_at', 'desc');

        // 分页
        $paginator = $query->paginate($pageSize, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'total' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'pageSize' => $paginator->perPage(),
            'totalPages' => $paginator->lastPage(),
        ];
    }

    /**
     * 获取即将过期的邀请码
     */
    public function getExpiringSoon(int $hours = 24): array
    {
        $expireTime = now()->addHours($hours);
        
        return InvitationCode::select(['id', 'code', 'title', 'expires_at'])
            ->where('status', CodeStatus::ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $expireTime)
            ->where('expires_at', '>', now())
            ->orderBy('expires_at')
            ->get()
            ->toArray();
    }

    /**
     * 获取已过期的邀请码
     */
    public function getExpired(): array
    {
        return InvitationCode::select(['id', 'code', 'title', 'expires_at'])
            ->where('status', CodeStatus::ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->orderBy('expires_at')
            ->get()
            ->toArray();
    }

    /**
     * 根据标签查找邀请码
     */
    public function findByTag(string $tagName, string $tagValue): array
    {
        return InvitationCode::whereJsonContains('tags', [
            'name' => $tagName,
            'value' => $tagValue
        ])->get()->toArray();
    }

    /**
     * 统计邀请码数量
     */
    public function countByInviter(Inviter $inviter): int
    {
        return InvitationCode::where('inviter_type', $inviter->type)
            ->where('inviter_id', $inviter->id)
            ->count();
    }

    /**
     * 获取热门邀请码
     */
    public function getPopular(int $limit = 10): array
    {
        return InvitationCode::select([
                'id', 'code', 'title', 'used_count', 'max_usage'
            ])
            ->where('status', CodeStatus::ACTIVE)
            ->where('used_count', '>', 0)
            ->orderBy('used_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 统计邀请码总数
     */
    public function count(array $filters = []): int
    {
        $query = InvitationCode::query();
        $this->applyFilters($query, $filters);
        return $query->count();
    }

    /**
     * 获取邀请码统计数据
     */
    public function getStatistics(array $filters = []): array
    {
        $query = InvitationCode::query();
        $this->applyFilters($query, $filters);

        $result = $query->selectRaw('
            COUNT(*) as total_count,
            SUM(used_count) as total_used,
            AVG(used_count) as avg_used,
            COUNT(CASE WHEN status = "active" THEN 1 END) as active_count,
            COUNT(CASE WHEN status = "disabled" THEN 1 END) as disabled_count,
            COUNT(CASE WHEN status = "expired" THEN 1 END) as expired_count,
            COUNT(CASE WHEN expires_at IS NOT NULL AND expires_at < NOW() THEN 1 END) as naturally_expired_count,
            COUNT(CASE WHEN max_usage > 0 AND used_count >= max_usage THEN 1 END) as usage_exhausted_count
        ')->first();

        return [
            'total_count' => (int) $result->total_count,
            'total_used' => (int) $result->total_used,
            'avg_used' => round((float) $result->avg_used, 2),
            'active_count' => (int) $result->active_count,
            'disabled_count' => (int) $result->disabled_count,
            'expired_count' => (int) $result->expired_count,
            'naturally_expired_count' => (int) $result->naturally_expired_count,
            'usage_exhausted_count' => (int) $result->usage_exhausted_count,
        ];
    }

    /**
     * 根据状态统计数量
     */
    public function countByStatus(): array
    {
        $result = InvitationCode::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'active' => $result['active'] ?? 0,
            'disabled' => $result['disabled'] ?? 0,
            'expired' => $result['expired'] ?? 0,
        ];
    }

    /**
     * 获取今日新增数量
     */
    public function getTodayCount(): int
    {
        return InvitationCode::whereDate('created_at', today())->count();
    }

    /**
     * 获取使用排行榜
     */
    public function getUsageRanking(int $limit = 10): array
    {
        return InvitationCode::select([
                'id', 'code', 'title', 'inviter_name', 'used_count', 'max_usage'
            ])
            ->where('used_count', '>', 0)
            ->orderBy('used_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 应用过滤条件
     */
    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['inviter_type'])) {
            $query->where('inviter_type', $filters['inviter_type']);
        }

        if (isset($filters['inviter_id'])) {
            $query->where('inviter_id', $filters['inviter_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['code'])) {
            $query->where('code', 'like', '%' . $filters['code'] . '%');
        }

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['generate_type'])) {
            $query->where('generate_type', $filters['generate_type']);
        }

        if (isset($filters['created_from'])) {
            $query->where('created_at', '>=', $filters['created_from']);
        }

        if (isset($filters['created_to'])) {
            $query->where('created_at', '<=', $filters['created_to']);
        }

        if (isset($filters['expires_soon'])) {
            $hours = (int) $filters['expires_soon'];
            $expireTime = now()->addHours($hours);
            $query->whereNotNull('expires_at')
                  ->where('expires_at', '<=', $expireTime)
                  ->where('expires_at', '>', now());
        }

        if (isset($filters['expired_only']) && $filters['expired_only']) {
            $query->whereNotNull('expires_at')
                  ->where('expires_at', '<', now());
        }
    }
} 