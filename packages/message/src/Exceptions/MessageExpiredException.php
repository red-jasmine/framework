<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 消息过期异常
 */
class MessageExpiredException extends MessageException
{
    public function __construct(
        string $messageId,
        \DateTimeInterface $expiresAt,
        int $code = 410,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: "消息已于 {$expiresAt->format('Y-m-d H:i:s')} 过期",
            errorCode: 'MESSAGE_EXPIRED',
            errorDetails: [
                'message_id' => $messageId,
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
                'expired_seconds_ago' => now()->getTimestamp() - $expiresAt->getTimestamp(),
            ],
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建消息过期异常
     */
    public static function withExpireTime(string $messageId, \DateTimeInterface $expiresAt): self
    {
        return new self($messageId, $expiresAt);
    }
}
