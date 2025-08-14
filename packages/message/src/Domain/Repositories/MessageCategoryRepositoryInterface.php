<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息分类写操作仓库接口
 */
interface MessageCategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据所属者查找分类
     */
    public function findByOwner(string $ownerId): array;

    /**
     * 根据业务线查找分类
     */
    public function findByBiz(string $biz): array;

    /**
     * 根据所属者和业务线查找分类
     */
    public function findByOwnerAndBiz(string $ownerId, string $biz): array;

    /**
     * 根据名称查找分类
     */
    public function findByName(string $name, string $ownerId, string $biz): ?MessageCategory;

    /**
     * 查找启用的分类
     */
    public function findEnabled(string $ownerId, ?string $biz = null): array;

    /**
     * 查找禁用的分类
     */
    public function findDisabled(string $ownerId, ?string $biz = null): array;

    /**
     * 批量更新排序
     */
    public function batchUpdateSort(array $sortData): int;

    /**
     * 批量启用分类
     */
    public function batchEnable(array $categoryIds): int;

    /**
     * 批量禁用分类
     */
    public function batchDisable(array $categoryIds): int;

    /**
     * 检查分类名称是否存在
     */
    public function existsByName(string $name, string $ownerId, string $biz, ?int $excludeId = null): bool;

    /**
     * 获取分类的最大排序值
     */
    public function getMaxSort(string $ownerId, string $biz): int;

    /**
     * 根据排序获取分类
     */
    public function findBySort(string $ownerId, string $biz, int $sort): ?MessageCategory;

    /**
     * 获取分类使用统计
     */
    public function getCategoryUsageStats(array $categoryIds = []): array;

    /**
     * 查找有消息的分类
     */
    public function findCategoriesWithMessages(string $ownerId, ?string $biz = null): array;

    /**
     * 查找无消息的分类
     */
    public function findCategoriesWithoutMessages(string $ownerId, ?string $biz = null): array;
}
