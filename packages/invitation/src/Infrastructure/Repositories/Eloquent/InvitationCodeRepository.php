<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Infrastructure\Repositories\Eloquent;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\Models\Enums\CodeStatus;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * 邀请码仓库实现
 */
final class InvitationCodeRepository extends EloquentRepository implements InvitationCodeRepositoryInterface
{
    protected static string $eloquentModelClass = InvitationCode::class;

    /**
     * 根据ID查找邀请码（基类已提供find方法）
     */
    public function findById(int $id): ?InvitationCode
    {
        return $this->find($id);
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
     * 保存邀请码（新增时使用store方法）
     */
    public function save(InvitationCode $invitationCode): void
    {
        if ($invitationCode->exists) {
            $this->update($invitationCode);
        } else {
            $this->store($invitationCode);
        }
    }



    /**
     * 分页查询邀请码（自定义方法，不与基类冲突）
     */
    public function paginateInvitationCodes(array $filters = [], int $page = 1, int $pageSize = 20): array
    {
        $query = InvitationCode::query();

        // 应用过滤条件
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
        
        return InvitationCode::where('status', CodeStatus::ACTIVE)
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
        return InvitationCode::where('status', CodeStatus::ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->orderBy('expires_at')
            ->get()
            ->toArray();
    }

    /**
     * 批量更新邀请码状态
     */
    public function batchUpdateStatus(array $ids, string $status): void
    {
        InvitationCode::whereIn('id', $ids)->update(['status' => $status]);
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
        return InvitationCode::where('status', CodeStatus::ACTIVE)
            ->orderBy('used_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 根据邀请人查找有效的邀请码
     */
    public function findActiveByInviter(\RedJasmine\Support\Contracts\UserInterface $inviter): ?InvitationCode
    {
        return InvitationCode::where('inviter_type', get_class($inviter))
            ->where('inviter_id', $inviter->id)
            ->where('status', CodeStatus::ACTIVE)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->first();
    }
} 