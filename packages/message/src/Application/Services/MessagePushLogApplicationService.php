<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services;

use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Message\Domain\Repositories\MessagePushLogRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessagePushLogTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 消息推送日志应用服务
 */
class MessagePushLogApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'message.push.log.application';
    protected static string $modelClass = MessagePushLog::class;

    public function __construct(
        public MessagePushLogRepositoryInterface $repository,
        public MessagePushLogTransformer $transformer
    ) {
    }

    protected static $macros = [
        'create' => \RedJasmine\Message\Application\Services\Commands\MessagePushLogCreateCommandHandler::class,
        'update' => \RedJasmine\Message\Application\Services\Commands\MessagePushLogUpdateCommandHandler::class,
        'delete' => \RedJasmine\Message\Application\Services\Commands\MessagePushLogDeleteCommandHandler::class,
        'updateResult' => \RedJasmine\Message\Application\Services\Commands\MessagePushLogUpdateResultCommandHandler::class,
        'retry' => \RedJasmine\Message\Application\Services\Commands\MessagePushLogRetryCommandHandler::class,
        'clean' => \RedJasmine\Message\Application\Services\Commands\MessagePushLogCleanCommandHandler::class,

        'find' => \RedJasmine\Message\Application\Services\Queries\MessagePushLogFindQueryHandler::class,
        'paginate' => \RedJasmine\Message\Application\Services\Queries\MessagePushLogPaginateQueryHandler::class,
        'statistics' => \RedJasmine\Message\Application\Services\Queries\MessagePushLogStatisticsQueryHandler::class,
        'performance' => \RedJasmine\Message\Application\Services\Queries\MessagePushLogPerformanceQueryHandler::class,
    ];

    /**
     * 根据消息ID获取推送日志
     */
    public function getByMessageId(int $messageId): array
    {
        return $this->repository->getByMessageId($messageId)->toArray();
    }

    /**
     * 根据渠道获取推送日志
     */
    public function getByChannel(string $channel, int $limit = 100): array
    {
        return $this->repository->getByChannel($channel, $limit)->toArray();
    }

    /**
     * 获取失败的推送日志
     */
    public function getFailedLogs(int $limit = 100): array
    {
        return $this->repository->getFailedLogs($limit)->toArray();
    }

    /**
     * 获取推送统计数据
     */
    public function getStatistics(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): array
    {
        return $this->repository->getStatistics($startDate, $endDate);
    }

    /**
     * 获取渠道性能报告
     */
    public function getPerformanceReport(string $channel, int $days = 7): array
    {
        return $this->repository->getPerformanceReport($channel, $days);
    }

    /**
     * 获取错误统计
     */
    public function getErrorStatistics(int $days = 7): array
    {
        return $this->repository->getErrorStatistics($days);
    }

    /**
     * 获取重试统计
     */
    public function getRetryStatistics(): array
    {
        return $this->repository->getRetryStatistics();
    }

    /**
     * 获取响应时间分布
     */
    public function getResponseTimeDistribution(string $channel): array
    {
        return $this->repository->getResponseTimeDistribution($channel);
    }

    /**
     * 清理过期日志
     */
    public function cleanExpiredLogs(int $days = 30): int
    {
        $before = now()->subDays($days);
        return $this->repository->cleanOldLogs($before);
    }

    /**
     * 批量更新推送结果
     */
    public function batchUpdateResult(array $results): int
    {
        $updated = 0;
        foreach ($results as $result) {
            if (isset($result['id'])) {
                $id = $result['id'];
                unset($result['id']);
                if ($this->repository->updatePushResult($id, $result)) {
                    $updated++;
                }
            }
        }
        return $updated;
    }

    /**
     * 批量重试失败的推送
     */
    public function batchRetryFailed(array $ids): int
    {
        return $this->repository->batchIncrementRetryCount($ids);
    }
}
