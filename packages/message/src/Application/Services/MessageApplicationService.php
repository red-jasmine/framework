<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services;

use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageReadRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessageTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 消息应用服务
 */
class MessageApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'message.application';
    protected static string $modelClass = Message::class;

    public function __construct(
        public MessageRepositoryInterface $repository,
        public MessageReadRepositoryInterface $readRepository,
        public MessageTransformer $transformer
    ) {
    }

    protected static array $macros = [
        'create' => \RedJasmine\Message\Application\Services\Commands\MessageCreateCommandHandler::class,
        'update' => \RedJasmine\Message\Application\Services\Commands\MessageUpdateCommandHandler::class,
        'delete' => \RedJasmine\Message\Application\Services\Commands\MessageDeleteCommandHandler::class,
        'markAsRead' => \RedJasmine\Message\Application\Services\Commands\MessageMarkAsReadCommandHandler::class,
        'archive' => \RedJasmine\Message\Application\Services\Commands\MessageArchiveCommandHandler::class,
        'send' => \RedJasmine\Message\Application\Services\Commands\MessageSendCommandHandler::class,
        'batchSend' => \RedJasmine\Message\Application\Services\Commands\MessageBatchSendCommandHandler::class,
        
        'find' => \RedJasmine\Message\Application\Services\Queries\MessageFindQueryHandler::class,
        'paginate' => \RedJasmine\Message\Application\Services\Queries\MessagePaginateQueryHandler::class,
        'list' => \RedJasmine\Message\Application\Services\Queries\MessageListQueryHandler::class,
        'statistics' => \RedJasmine\Message\Application\Services\Queries\MessageStatisticsQueryHandler::class,
    ];

    /**
     * 获取用户未读消息数量
     */
    public function getUnreadCount(string $receiverId, ?string $biz = null): int
    {
        return $this->readRepository->getUnreadCount($receiverId, $biz);
    }

    /**
     * 获取用户消息统计
     */
    public function getUserStatistics(string $receiverId): array
    {
        return $this->readRepository->getStatistics($receiverId);
    }

    /**
     * 获取高优先级未读消息
     */
    public function getHighPriorityUnread(string $receiverId, int $limit = 10): array
    {
        return $this->readRepository->getHighPriorityUnread($receiverId, $limit)->toArray();
    }

    /**
     * 获取即将过期的消息
     */
    public function getExpiringMessages(int $hours = 24): array
    {
        return $this->readRepository->getExpiringMessages($hours)->toArray();
    }

    /**
     * 清理过期消息
     */
    public function cleanExpiredMessages(): int
    {
        $expiredBefore = now()->subHours(24);
        return $this->repository->deleteExpiredMessages($expiredBefore);
    }

    /**
     * 批量标记为已读
     */
    public function batchMarkAsRead(array $messageIds, string $readerId): int
    {
        return $this->repository->markAsRead($messageIds, $readerId);
    }

    /**
     * 批量归档消息
     */
    public function batchArchive(array $messageIds): int
    {
        return $this->repository->archiveMessages($messageIds);
    }
}
