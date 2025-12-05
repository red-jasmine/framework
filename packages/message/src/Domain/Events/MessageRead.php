<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Support\Domain\Contracts\UserInterface;

/**
 * 消息阅读事件
 */
class MessageRead
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Message $message,
        public readonly UserInterface $reader
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.read';
    }

    /**
     * 获取消息ID
     */
    public function getMessageId(): string
    {
        return $this->message->id;
    }

    /**
     * 获取阅读者ID
     */
    public function getReaderId(): string
    {
        return (string) $this->reader->getKey();
    }

    /**
     * 获取接收人ID
     */
    public function getReceiverId(): string
    {
        return $this->message->receiver_id;
    }

    /**
     * 获取业务线
     */
    public function getBiz(): string
    {
        return $this->message->biz->value;
    }

    /**
     * 获取阅读时间
     */
    public function getReadAt(): \DateTimeInterface
    {
        return $this->message->read_at ?? now();
    }

    /**
     * 是否为阅后即焚消息
     */
    public function isBurnAfterRead(): bool
    {
        return $this->message->is_burn_after_read;
    }

    /**
     * 获取消息类型
     */
    public function getMessageType(): string
    {
        return $this->message->type->value;
    }

    /**
     * 获取消息优先级
     */
    public function getMessagePriority(): string
    {
        return $this->message->priority->value;
    }

    /**
     * 获取消息分类ID
     */
    public function getCategoryId(): ?int
    {
        return $this->message->category_id;
    }

    /**
     * 计算消息存活时间（从创建到阅读的时间）
     */
    public function getMessageLifetime(): int
    {
        if (!$this->message->created_at) {
            return 0;
        }

        $readAt = $this->getReadAt();
        return $readAt->getTimestamp() - $this->message->created_at->getTimestamp();
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId(),
            'message_id' => $this->getMessageId(),
            'reader_id' => $this->getReaderId(),
            'receiver_id' => $this->getReceiverId(),
            'biz' => $this->getBiz(),
            'category_id' => $this->getCategoryId(),
            'message_type' => $this->getMessageType(),
            'message_priority' => $this->getMessagePriority(),
            'is_burn_after_read' => $this->isBurnAfterRead(),
            'message_lifetime' => $this->getMessageLifetime(),
            'read_at' => $this->getReadAt()->toISOString(),
        ];
    }
}
