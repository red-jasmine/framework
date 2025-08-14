<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Message\Domain\Models\ValueObjects\ErrorInfo;

/**
 * 消息推送失败事件
 */
class MessagePushFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Message $message,
        public readonly PushChannelEnum $channel,
        public readonly ErrorInfo $error,
        public readonly ?MessagePushLog $pushLog = null
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.push.failed';
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
     * 获取推送渠道
     */
    public function getChannel(): string
    {
        return $this->channel->value;
    }

    /**
     * 获取错误码
     */
    public function getErrorCode(): string
    {
        return $this->error->errorCode;
    }

    /**
     * 获取错误信息
     */
    public function getErrorMessage(): string
    {
        return $this->error->errorMessage;
    }

    /**
     * 获取错误级别
     */
    public function getErrorLevel(): string
    {
        return $this->error->errorLevel;
    }

    /**
     * 是否可恢复
     */
    public function isRecoverable(): bool
    {
        return $this->error->recoverable;
    }

    /**
     * 是否为关键错误
     */
    public function isCritical(): bool
    {
        return $this->error->isCritical();
    }

    /**
     * 获取重试次数
     */
    public function getRetryCount(): int
    {
        return $this->pushLog?->retry_count ?? 0;
    }

    /**
     * 是否可以重试
     */
    public function canRetry(): bool
    {
        return $this->pushLog?->canRetry() ?? false;
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
            'channel' => $this->getChannel(),
            'error_code' => $this->getErrorCode(),
            'error_message' => $this->getErrorMessage(),
            'error_level' => $this->getErrorLevel(),
            'is_recoverable' => $this->isRecoverable(),
            'is_critical' => $this->isCritical(),
            'retry_count' => $this->getRetryCount(),
            'can_retry' => $this->canRetry(),
            'failed_at' => $this->error->errorTime->toISOString(),
        ];
    }
}
