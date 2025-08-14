<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 消息推送日志只读仓库接口
 */
interface MessagePushLogReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据ID列表查找推送日志
     */
    public function findList(array $ids): \Illuminate\Support\Collection;

    /**
     * 获取推送统计数据
     */
    public function getStatistics(array $filters = []): array;

    /**
     * 获取推送成功率报告
     */
    public function getSuccessRateReport(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 获取各渠道推送效果对比
     */
    public function getChannelComparisonReport(array $filters = []): array;

    /**
     * 获取推送响应时间统计
     */
    public function getResponseTimeStats(array $filters = []): array;

    /**
     * 获取推送错误分析报告
     */
    public function getErrorAnalysisReport(array $filters = []): array;

    /**
     * 获取推送趋势数据
     */
    public function getTrendData(\DateTimeInterface $start, \DateTimeInterface $end, string $groupBy = 'day'): array;

    /**
     * 获取推送量统计
     */
    public function getVolumeStats(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 获取重试统计
     */
    public function getRetryStats(array $filters = []): array;

    /**
     * 获取推送日志详细信息
     */
    public function getLogDetails(int $logId): ?array;

    /**
     * 搜索推送日志
     */
    public function searchLogs(string $keyword, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * 获取推送性能报告
     */
    public function getPerformanceReport(array $filters = []): array;

    /**
     * 获取推送异常报告
     */
    public function getAnomalyReport(array $filters = []): array;

    /**
     * 获取推送质量评分
     */
    public function getQualityScore(array $filters = []): float;

    /**
     * 获取推送热力图数据
     */
    public function getHeatmapData(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 导出推送日志数据
     */
    public function exportLogs(array $filters = [], string $format = 'csv'): string;
}
