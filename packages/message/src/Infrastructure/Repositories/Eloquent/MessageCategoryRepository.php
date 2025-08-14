<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\Repositories\Eloquent;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * 消息分类仓库实现
 */
class MessageCategoryRepository extends EloquentRepository implements MessageCategoryRepositoryInterface
{
    protected static string $eloquentModelClass = MessageCategory::class;

    /**
     * 根据所属者查找分类
     */
    public function findByOwner(string $ownerId): array
    {
        return MessageCategory::where('owner_id', $ownerId)
            ->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 根据业务线查找分类
     */
    public function findByBiz(string $biz): array
    {
        return MessageCategory::where('biz', $biz)
            ->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 根据所属者和业务线查找分类
     */
    public function findByOwnerAndBiz(string $ownerId, string $biz): array
    {
        return MessageCategory::where('owner_id', $ownerId)
            ->where('biz', $biz)
            ->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 根据名称查找分类
     */
    public function findByName(string $name, string $ownerId, string $biz): ?MessageCategory
    {
        return MessageCategory::where('name', $name)
            ->where('owner_id', $ownerId)
            ->where('biz', $biz)
            ->first();
    }

    /**
     * 查找启用的分类
     */
    public function findEnabled(string $ownerId, ?string $biz = null): array
    {
        $query = MessageCategory::where('owner_id', $ownerId)
            ->where('status', 'enable');

        if ($biz) {
            $query->where('biz', $biz);
        }

        return $query->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 查找禁用的分类
     */
    public function findDisabled(string $ownerId, ?string $biz = null): array
    {
        $query = MessageCategory::where('owner_id', $ownerId)
            ->where('status', 'disable');

        if ($biz) {
            $query->where('biz', $biz);
        }

        return $query->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 批量更新排序
     */
    public function batchUpdateSort(array $sortData): int
    {
        $updated = 0;
        foreach ($sortData as $data) {
            if (isset($data['id']) && isset($data['sort'])) {
                $updated += MessageCategory::where('id', $data['id'])
                    ->update(['sort' => $data['sort']]);
            }
        }
        return $updated;
    }

    /**
     * 批量启用分类
     */
    public function batchEnable(array $categoryIds): int
    {
        return MessageCategory::whereIn('id', $categoryIds)
            ->update(['status' => 'enable']);
    }

    /**
     * 批量禁用分类
     */
    public function batchDisable(array $categoryIds): int
    {
        return MessageCategory::whereIn('id', $categoryIds)
            ->update(['status' => 'disable']);
    }

    /**
     * 检查分类名称是否存在
     */
    public function existsByName(string $name, string $ownerId, string $biz, ?int $excludeId = null): bool
    {
        $query = MessageCategory::where('name', $name)
            ->where('owner_id', $ownerId)
            ->where('biz', $biz);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * 获取分类的最大排序值
     */
    public function getMaxSort(string $ownerId, string $biz): int
    {
        return MessageCategory::where('owner_id', $ownerId)
            ->where('biz', $biz)
            ->max('sort') ?? 0;
    }

    /**
     * 根据排序获取分类
     */
    public function findBySort(string $ownerId, string $biz, int $sort): ?MessageCategory
    {
        return MessageCategory::where('owner_id', $ownerId)
            ->where('biz', $biz)
            ->where('sort', $sort)
            ->first();
    }

    /**
     * 获取分类使用统计
     */
    public function getCategoryUsageStats(array $categoryIds = []): array
    {
        $query = MessageCategory::withCount('messages');

        if (!empty($categoryIds)) {
            $query->whereIn('id', $categoryIds);
        }

        return $query->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'messages_count' => $category->messages_count,
                ];
            })
            ->toArray();
    }

    /**
     * 查找有消息的分类
     */
    public function findCategoriesWithMessages(string $ownerId, ?string $biz = null): array
    {
        $query = MessageCategory::where('owner_id', $ownerId)
            ->has('messages');

        if ($biz) {
            $query->where('biz', $biz);
        }

        return $query->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 查找无消息的分类
     */
    public function findCategoriesWithoutMessages(string $ownerId, ?string $biz = null): array
    {
        $query = MessageCategory::where('owner_id', $ownerId)
            ->doesntHave('messages');

        if ($biz) {
            $query->where('biz', $biz);
        }

        return $query->orderBy('sort')
            ->get()
            ->toArray();
    }
}
