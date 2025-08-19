<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Message;

use RedJasmine\Message\Application\Services\Commands\MessageArchiveCommandHandler;
use RedJasmine\Message\Application\Services\Commands\MessageCreateCommandHandler;
use RedJasmine\Message\Application\Services\Message\Commands\MessageAllMarkAsReadCommandHandler;
use RedJasmine\Message\Application\Services\Message\Commands\MessageMarkAsReadCommand;
use RedJasmine\Message\Application\Services\Message\Commands\MessageMarkAsReadCommandHandler;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageReadRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessageTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 消息应用服务
 * @method markAsRead(MessageMarkAsReadCommand $command)
 * @method allMarkAsRead(MessageMarkAsReadCommand $command)
 */
class MessageApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'message.application';
    protected static string $modelClass     = Message::class;

    public function __construct(
        public MessageRepositoryInterface $repository,
        public MessageReadRepositoryInterface $readRepository,
        public MessageTransformer $transformer
    ) {
    }

    protected static $macros = [
        'create'        => MessageCreateCommandHandler::class,
        'markAsRead'    => MessageMarkAsReadCommandHandler::class,
        'allMarkAsRead' => MessageAllMarkAsReadCommandHandler::class,

    ];

    /**
     * 获取用户未读消息数量
     */
    public function getUnreadCount(UserInterface $owner, ?string $biz = null) : int
    {
        return $this->readRepository->getUnreadCount($owner, $biz);
    }

    /**
     * 获取用户消息统计
     */
    public function getUserStatistics(string $receiverId) : array
    {
        return $this->readRepository->getStatistics($receiverId);
    }

    /**
     * 获取高优先级未读消息
     */
    public function getHighPriorityUnread(string $receiverId, int $limit = 10) : array
    {
        return $this->readRepository->getHighPriorityUnread($receiverId, $limit)->toArray();
    }

    /**
     * 获取即将过期的消息
     */
    public function getExpiringMessages(int $hours = 24) : array
    {
        return $this->readRepository->getExpiringMessages($hours)->toArray();
    }

    /**
     * 清理过期消息
     */
    public function cleanExpiredMessages() : int
    {
        $expiredBefore = now()->subHours(24);
        return $this->repository->deleteExpiredMessages($expiredBefore);
    }

    /**
     * 批量标记为已读
     */
    public function batchMarkAsRead(array $messageIds, string $readerId) : int
    {
        return $this->repository->markAsRead($messageIds, $readerId);
    }

    /**
     * 批量归档消息
     */
    public function batchArchive(array $messageIds) : int
    {
        return $this->repository->archiveMessages($messageIds);
    }
}
