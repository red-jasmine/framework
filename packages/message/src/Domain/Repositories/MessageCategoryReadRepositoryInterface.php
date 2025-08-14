<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 消息分类只读仓库接口
 */
interface MessageCategoryReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据ID列表查找分类
     */
    public function findList(array $ids): \Illuminate\Support\Collection;

    /**
     * 获取分类树结构
     */
    public function getTree(string $ownerId, ?string $biz = null): array;

    /**
     * 获取启用的分类列表
     */
    public function getEnabledList(string $ownerId, ?string $biz = null): \Illuminate\Support\Collection;

    /**
     * 获取分类统计信息
     */
    public function getStatistics(array $filters = []): array;

    /**
     * 搜索分类
     */
    public function searchCategories(string $keyword, array $filters = []): \Illuminate\Support\Collection;

    /**
     * 获取分类使用排行
     */
    public function getCategoryRanking(int $limit = 10, array $filters = []): array;

    /**
     * 获取分类消息数量统计
     */
    public function getMessageCountStats(array $categoryIds = []): array;

    /**
     * 获取分类活跃度统计
     */
    public function getActivityStats(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 按业务线分组获取分类
     */
    public function getGroupedByBiz(string $ownerId): array;

    /**
     * 获取最近使用的分类
     */
    public function getRecentlyUsed(string $ownerId, int $limit = 5): \Illuminate\Support\Collection;

    /**
     * 获取热门分类
     */
    public function getPopularCategories(int $limit = 10, array $filters = []): \Illuminate\Support\Collection;

    /**
     * 获取分类详细统计
     */
    public function getCategoryDetailStats(int $categoryId): array;
}
