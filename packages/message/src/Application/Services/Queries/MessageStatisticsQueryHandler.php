<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 消息统计查询处理器
 */
class MessageStatisticsQueryHandler extends QueryHandler
{
    public function __construct(
        protected MessageApplicationService $service,
    ) {
    }

    /**
     * 处理消息统计查询
     */
    public function handle(MessageStatisticsQuery $query): array
    {
        // 基础统计
        $statistics = $this->getBasicStatistics($query);

        // 按维度分组统计
        if ($query->isGroupByBiz()) {
            $statistics['by_biz'] = $this->getStatisticsByBiz($query);
        }

        if ($query->isGroupByCategory()) {
            $statistics['by_category'] = $this->getStatisticsByCategory($query);
        }

        if ($query->isGroupByDate()) {
            $statistics['by_date'] = $this->getStatisticsByDate($query);
        }

        return $statistics;
    }

    /**
     * 获取基础统计
     */
    protected function getBasicStatistics(MessageStatisticsQuery $query): array
    {
        $receiverId = $query->receiverId ?: $query->getOwnerId();
        
        if (!$receiverId) {
            return [];
        }

        // 使用只读仓库获取统计数据
        $stats = $this->service->readRepository->getStatistics($receiverId);

        // 如果指定了时间范围，需要重新计算
        if ($query->hasDateRange()) {
            $stats = $this->getStatisticsWithDateRange($query, $receiverId);
        }

        return $this->formatBasicStatistics($stats, $query->getDimensions());
    }

    /**
     * 获取带时间范围的统计
     */
    protected function getStatisticsWithDateRange(MessageStatisticsQuery $query, string $receiverId): array
    {
        $dateRange = $query->getDateRange();
        
        $builder = $this->service->readRepository->query()
            ->where('receiver_id', $receiverId);

        if ($dateRange['start']) {
            $builder->where('created_at', '>=', $dateRange['start']);
        }

        if ($dateRange['end']) {
            $builder->where('created_at', '<=', $dateRange['end']);
        }

        // 添加其他过滤条件
        if ($query->biz) {
            $builder->where('biz', $query->biz);
        }

        if ($query->categoryId) {
            $builder->where('category_id', $query->categoryId);
        }

        // 计算各种统计
        $total = $builder->count();
        $unread = (clone $builder)->where('status', 'unread')->count();
        $read = (clone $builder)->where('status', 'read')->count();
        $archived = (clone $builder)->where('status', 'archived')->count();
        $urgent = (clone $builder)->where('is_urgent', true)->count();
        $expired = (clone $builder)->whereNotNull('expires_at')
                                  ->where('expires_at', '<', now())
                                  ->count();

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'archived' => $archived,
            'urgent' => $urgent,
            'expired' => $expired,
        ];
    }

    /**
     * 按业务线统计
     */
    protected function getStatisticsByBiz(MessageStatisticsQuery $query): array
    {
        $receiverId = $query->receiverId ?: $query->getOwnerId();
        
        if (!$receiverId) {
            return [];
        }

        return $this->service->readRepository->getStatisticsByBiz();
    }

    /**
     * 按分类统计
     */
    protected function getStatisticsByCategory(MessageStatisticsQuery $query): array
    {
        $receiverId = $query->receiverId ?: $query->getOwnerId();
        
        if (!$receiverId) {
            return [];
        }

        $builder = $this->service->readRepository->query()
            ->where('receiver_id', $receiverId);

        // 应用时间范围
        if ($query->hasDateRange()) {
            $dateRange = $query->getDateRange();
            if ($dateRange['start']) {
                $builder->where('created_at', '>=', $dateRange['start']);
            }
            if ($dateRange['end']) {
                $builder->where('created_at', '<=', $dateRange['end']);
            }
        }

        return $builder->selectRaw('
                category_id, 
                COUNT(*) as total,
                SUM(CASE WHEN status = "unread" THEN 1 ELSE 0 END) as unread,
                SUM(CASE WHEN status = "read" THEN 1 ELSE 0 END) as read,
                SUM(CASE WHEN is_urgent = 1 THEN 1 ELSE 0 END) as urgent
            ')
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id')
            ->toArray();
    }

    /**
     * 按日期统计
     */
    protected function getStatisticsByDate(MessageStatisticsQuery $query): array
    {
        $receiverId = $query->receiverId ?: $query->getOwnerId();
        
        if (!$receiverId) {
            return [];
        }

        $dateFormat = $this->getDateFormatForDatabase($query->getDateFormat());
        
        $builder = $this->service->readRepository->query()
            ->where('receiver_id', $receiverId);

        // 应用时间范围
        if ($query->hasDateRange()) {
            $dateRange = $query->getDateRange();
            if ($dateRange['start']) {
                $builder->where('created_at', '>=', $dateRange['start']);
            }
            if ($dateRange['end']) {
                $builder->where('created_at', '<=', $dateRange['end']);
            }
        }

        return $builder->selectRaw("
                DATE_FORMAT(created_at, '{$dateFormat}') as date,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read,
                SUM(CASE WHEN is_urgent = 1 THEN 1 ELSE 0 END) as urgent
            ")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();
    }

    /**
     * 格式化基础统计数据
     */
    protected function formatBasicStatistics(array $stats, array $dimensions): array
    {
        $result = [];
        
        foreach ($dimensions as $dimension) {
            if (isset($stats[$dimension])) {
                $result[$dimension] = $stats[$dimension];
            }
        }

        return $result;
    }

    /**
     * 获取数据库日期格式
     */
    protected function getDateFormatForDatabase(string $phpFormat): string
    {
        $formatMap = [
            'Y-m-d' => '%Y-%m-%d',
            'Y-m' => '%Y-%m',
            'Y' => '%Y',
            'Y-m-d H:i' => '%Y-%m-%d %H:%i',
        ];

        return $formatMap[$phpFormat] ?? '%Y-%m-%d';
    }
}
