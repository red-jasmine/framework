<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 消息只读仓库接口
 */
interface MessageReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据ID列表查找消息
     */
    public function findList(array $ids): \Illuminate\Support\Collection;

    /**
     * 获取用户未读消息数量
     */
    public function getUnreadCount(string $receiverId, ?string $biz = null): int;

    /**
     * 获取用户各业务线未读消息数量
     */
    public function getUnreadCountByBiz(string $receiverId): array;

    /**
     * 获取用户各分类未读消息数量
     */
    public function getUnreadCountByCategory(string $receiverId, ?string $biz = null): array;

    /**
     * 获取消息统计数据
     */
    public function getStatistics(array $filters = []): array;

    /**
     * 获取推送统计数据
     */
    public function getPushStatistics(array $filters = []): array;

    /**
     * 全文搜索消息
     */
    public function searchMessages(string $keyword, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * 获取用户最近的消息
     */
    public function getRecentMessages(string $receiverId, int $limit = 10): \Illuminate\Support\Collection;

    /**
     * 获取热门消息（按阅读量）
     */
    public function getPopularMessages(int $limit = 10, array $filters = []): \Illuminate\Support\Collection;

    /**
     * 获取消息趋势数据
     */
    public function getTrendData(\DateTimeInterface $start, \DateTimeInterface $end, string $groupBy = 'day'): array;

    /**
     * 获取用户消息行为统计
     */
    public function getUserBehaviorStats(string $receiverId): array;

    /**
     * 获取分类消息统计
     */
    public function getCategoryStats(array $categoryIds = []): array;

    /**
     * 获取模板使用统计
     */
    public function getTemplateUsageStats(array $templateIds = []): array;

    /**
     * 获取推送渠道效果统计
     */
    public function getChannelEffectivenessStats(array $filters = []): array;

    /**
     * 获取消息发送量统计
     */
    public function getSendVolumeStats(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 获取消息阅读率统计
     */
    public function getReadRateStats(array $filters = []): array;

    /**
     * 查找相似消息
     */
    public function findSimilarMessages(string $messageId, int $limit = 5): \Illuminate\Support\Collection;

    /**
     * 获取过期消息
     */
    public function getExpiredMessages(int $limit = 100): \Illuminate\Support\Collection;

    /**
     * 获取长期未读消息
     */
    public function getLongUnreadMessages(int $days = 30, int $limit = 100): \Illuminate\Support\Collection;
}
