<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\Message;

/**
 * 消息创建事件
 */
class MessageCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Message $message
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.created';
    }

    /**
     * 获取消息ID
     */
    public function getMessageId(): string
    {
        return $this->message->id;
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
     * 是否为紧急消息
     */
    public function isUrgent(): bool
    {
        return $this->message->isUrgent();
    }

    /**
     * 是否为高优先级消息
     */
    public function isHighPriority(): bool
    {
        return $this->message->isHighPriority();
    }

    /**
     * 获取推送渠道
     */
    public function getPushChannels(): array
    {
        return $this->message->channels ?? [];
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId(),
            'message_id' => $this->getMessageId(),
            'receiver_id' => $this->getReceiverId(),
            'biz' => $this->getBiz(),
            'title' => $this->message->title,
            'type' => $this->message->type->value,
            'priority' => $this->message->priority->value,
            'is_urgent' => $this->isUrgent(),
            'channels' => $this->getPushChannels(),
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}
