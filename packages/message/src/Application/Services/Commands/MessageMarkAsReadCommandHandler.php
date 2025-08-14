<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Message\Domain\Events\MessageRead;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * 标记消息为已读命令处理器
 */
class MessageMarkAsReadCommandHandler extends CommandHandler
{
    public function __construct(
        protected MessageApplicationService $service,
    ) {
    }

    /**
     * 处理标记为已读命令
     */
    public function handle(MessageMarkAsReadCommand $command): int
    {
        $this->beginDatabaseTransaction();

        try {
            // 验证命令数据
            $command->validate();

            $updatedCount = 0;

            if ($command->isMarkAll()) {
                // 标记所有未读消息为已读
                $updatedCount = $this->markAllAsRead($command);
            } else {
                // 标记指定消息为已读
                $messageIds = $command->getMessageIds();
                $readerId = $command->getReaderId();
                
                $updatedCount = $this->service->repository->markAsRead($messageIds, $readerId);

                // 发布消息已读事件
                foreach ($messageIds as $messageId) {
                    $message = $this->service->repository->find($messageId);
                    if ($message) {
                        event(new MessageRead($message, $command->reader));
                    }
                }
            }

            $this->commitDatabaseTransaction();

            return $updatedCount;

        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }

    /**
     * 标记所有消息为已读
     */
    protected function markAllAsRead(MessageMarkAsReadCommand $command): int
    {
        $readerId = $command->getReaderId();
        
        // 构建查询条件
        $query = $this->service->readRepository->query()
            ->where('receiver_id', $readerId)
            ->where('status', 'unread');

        // 如果指定了业务线
        if ($command->getBiz()) {
            $query->where('biz', $command->getBiz());
        }

        // 如果指定了分类
        if ($command->getCategoryId()) {
            $query->where('category_id', $command->getCategoryId());
        }

        // 获取所有未读消息ID
        $messageIds = $query->pluck('id')->toArray();

        if (empty($messageIds)) {
            return 0;
        }

        // 批量更新为已读
        $updatedCount = $this->service->repository->markAsRead($messageIds, $readerId);

        // 发布消息已读事件
        foreach ($messageIds as $messageId) {
            $message = $this->service->repository->find($messageId);
            if ($message) {
                event(new MessageRead($message, $command->reader));
            }
        }

        return $updatedCount;
    }

    /**
     * 验证读取权限
     */
    protected function validateReadPermission(MessageMarkAsReadCommand $command): void
    {
        // 验证用户是否有权限读取指定的消息
        if (!$command->isMarkAll()) {
            $messageIds = $command->getMessageIds();
            $readerId = $command->getReaderId();

            foreach ($messageIds as $messageId) {
                $message = $this->service->repository->find($messageId);
                if (!$message) {
                    throw new \InvalidArgumentException("消息 {$messageId} 不存在");
                }

                if ($message->receiver_id !== $readerId) {
                    throw new \InvalidArgumentException("没有权限读取消息 {$messageId}");
                }

                if ($message->isExpired()) {
                    throw new \InvalidArgumentException("消息 {$messageId} 已过期");
                }
            }
        }
    }

    /**
     * 处理阅后即焚消息
     */
    protected function handleBurnAfterRead(array $messageIds): void
    {
        // 查找阅后即焚的消息
        $burnMessages = $this->service->readRepository->query()
            ->whereIn('id', $messageIds)
            ->where('is_burn_after_read', true)
            ->get();

        if ($burnMessages->isNotEmpty()) {
            $burnMessageIds = $burnMessages->pluck('id')->toArray();
            // 删除阅后即焚的消息
            $this->service->repository->deleteBurnAfterReadMessages($burnMessageIds);
        }
    }
}
